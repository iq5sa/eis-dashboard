<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class AppointmentsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    //

    // POST BR(E)AD
    public function update(Request $request, $id)
    {


        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);


        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        $user_id = $data->user_id;

        $user_fcm_token = User::find($user_id)->fcm_token;
        $response = Http::withHeaders([

            "Content-Type"=>"application/json",
            "Authorization"=>"key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $user_fcm_token,
            "priority"=>"high",
            "notification"=>[
                "title"=>"تم تحديد الموعد",
                "body"=>"بتاريخ: " . $data->book_at,
                "sound"=>"default"
            ],
            "data"=>[
                "type"=>"appointment"
            ]
        ]);


        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

}
