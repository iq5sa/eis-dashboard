<?php

namespace App\Http\Controllers;

use App\Jobs\FcmNotificationsJob;
use App\Models\FcmTokens;
use App\Models\User;
use App\Student;
use App\Utilities\FirebaseFcm;
use App\Utilities\NotificationsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;

class NotificationsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    //

    public function store(Request $request)
    {


        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();



        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();

        if($request->grade_id == null){
            $request["grade_id"] = 0;
        }


        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());



        event(new BreadDataAdded($dataType, $data));

        //send notification

        //TODO:: Send notification


        $params = [
            "parent_id"=>$request->parent_id,
            "grade_id"=>$data->grade_id,
            "notification"=>[
                "title"=>$request->title,
                "body"=>$request->desc
            ]
        ];

        //Artisan::call("queue:work");

        FcmNotificationsJob::dispatch($params);


//        if($request->parent_id !=null){
//
//            //send to the parents
//            $firebasefcm = new firebasefcm();
//            $firebasefcm->pushForSingleUser($request->parent_id,NotificationsData::custom(["title"=>$request->title,"body"=>$request->desc]));
//
//        }else{
//            if ($data->grade_id != null) {
//
//                //send by grade
//
//
//                $firebasefcm = new firebasefcm();
//                $firebasefcm->pushForGrade($data["grade_id"],NotificationsData::custom(["title"=>$request->title,"body"=>$request->desc]));
//
//
//            }else{
//                $firebasefcm = new firebasefcm();
//                $firebasefcm->pushForAll(NotificationsData::custom(["title"=>$request->title,"body"=>$request->desc]));
//
//            }
//        }


        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message' => __('voyager::generic.successfully_added_new') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }


    }
}
