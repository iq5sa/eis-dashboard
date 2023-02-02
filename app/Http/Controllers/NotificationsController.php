<?php

namespace App\Http\Controllers;

use App\Models\FcmTokens;
use App\Models\User;
use App\Student;
use Illuminate\Http\Request;
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


        if($request->parent_id !=null){
            $findUser = User::find($request->parent_id);
            $response = Http::withHeaders([

                "Content-Type" => "application/json",
                "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $findUser->fcm_token,
                "priority" => "high",
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->desc,
                    "sound" => "default"
                ],
                "data"=>[
                    "type"=>"announcements"
                ]
            ]);
        }else{
            if ($data->grade_id != null) {

                //send by grade

                $students = DB::table("students")->where("level_id", "=", $data->grade_id)
                    ->join("users", "students.parents_id", "=", "users.id")->get();

                $uniqueStudents = $students->unique(function ($item) {

                    return $item;
                });



                foreach ($uniqueStudents as $token) {
                    $response = Http::withHeaders([

                        "Content-Type" => "application/json",
                        "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to' => $token->fcm_token,
                        "priority" => "high",
                        "notification" => [
                            "title" => $request->title,
                            "body" => $request->desc,
                            "sound" => "default"
                        ],
                        "data"=>[
                            "type"=>"announcements"
                        ]
                    ]);
                }


            }else{

                $all_students = User::where("role_id","=","3")->get();

                foreach ($all_students as $token) {
                    $response = Http::withHeaders([

                        "Content-Type" => "application/json",
                        "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to' => $token["fcm_token"],
                        "priority" => "high",
                        "notification" => [
                            "title" => $request->title,
                            "body" => $request->desc,
                            "sound" => "default"
                        ]
                    ]);
                }
            }
        }


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
