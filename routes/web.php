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


Route::get('/send/all',function (){
    $all_students = User::where("role_id","=","3")->get();

    return $all_students;

    //classes
    //A 7
    //B 8
    //C 9


   //Select Grade 4(20)  Class A,b(7,8)

//    $students = \App\Student::where("level_id","=","17")->where("class_id","=","8")->get();
//
//
//    $title = "Last Day of Term 1 - اخر يوم في الفصل الدراسي الاول";
//    $body = "Dear parents,
//
//On the last day of school, primary students may share snacks (juice, crackers, chips,etc) if they can. Please note that SNACKS ONLY, food is not allowed.
//
//Regards,
//
//اولياء الامور الاعزاء،
//
//في اخر يوم من هذا الفصل، يمكن لطلبة المرحلة الابتدائية ،اذا رغبوا بذلك، احضار وجبات خفيفة (عصير ، بسكت ، جبس،الخ). يرجى التنويه بأنه لايُسمح باحضار طعام .
//
//مع اطيب التحيات،";
//
//    foreach ($students as $student){
//        $response = Http::withHeaders([
//
//            "Content-Type" => "application/json",
//            "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
//        ])->post('https://fcm.googleapis.com/fcm/send', [
//            'to' => $student->parents->fcm_token,
//            "priority" => "high",
//            "notification" => [
//                "title" => $title,
//                "body" => $body,
//                "sound" => "default"
//            ],
//            "data"=>[
//                "type"=>"announcements"
//            ]
//        ]);
//
//    }
//
////    $saveNotification = new \App\Notification();
////    $saveNotification->title = $title;
////    $saveNotification->desc = $body;
////    $saveNotification->grade_id = 17;
////    $saveNotification->save();
//
//    return "sent";



});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
