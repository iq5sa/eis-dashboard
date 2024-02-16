<?php

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\FcmTokens;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/','/admin');
Route::get('/send/notification',function (){

    $fcm_tokens = \App\Models\FcmTokens::all();

    foreach ($fcm_tokens as $token){
        $response = Http::withHeaders([

            "Content-Type" => "application/json",
            "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token->fcm_token,
            "priority" => "high",
            "notification" => [
                "title" => "Homework reminder",
                "body" => "Please open app to check today’s homework.",
                "sound" => "default"
            ],
            "data"=>[
                "type"=>"homework"
            ]
        ]);
    }

});




Route::get("grade", function () {

    //

});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
