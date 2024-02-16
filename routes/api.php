<?php

use App\Http\Controllers\Api\ActivitiesController;
use App\Http\Controllers\Api\AnnouncementsController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\SlidersController;
use App\Http\Controllers\Api\StudentBehaviorController;
use App\Http\Controllers\Api\TicketsController;
use App\Http\Controllers\Api\Timetable;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\FcmTokensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(UsersController::class)->group(function (){
    Route::group(["prefix"=>"user"],function (){
        Route::post('/create',"create");
        Route::post('/login',"login");
        Route::get('/all',"all")->middleware("auth:sanctum");
        Route::get("/profile","profile")->middleware("auth:sanctum");
        Route::get("/academy","getStudentAcademy")->middleware("auth:sanctum");
    });

    //Homework

    Route::get("/homework",[HomeworkController::class,"getHomework"])->middleware("auth:sanctum");

    //Tickets
    Route::post("/ticket/submit",[TicketsController::class,"store"])->middleware("auth:sanctum");
    Route::get("/tickets",[TicketsController::class,"getTickets"])->middleware("auth:sanctum");


    //book appointment
    Route::post("/appointment/book",[AppointmentController::class,"store"])->middleware("auth:sanctum");
    Route::get("/appointments",[AppointmentController::class,"show"])->middleware("auth:sanctum");

    //Announcements
    Route::get("/announcements",[AnnouncementsController::class,"getAll"])->middleware("auth:sanctum");

    //Announcements
    Route::get("/activities",[ActivitiesController::class,"show"]);

    //Sliders
    Route::get("/sliders",[SlidersController::class,"show"]);

    //Timetable
    Route::get("/timetable",[Timetable::class,"show"])->middleware("auth:sanctum");

    //Fcm Tokens *save
    Route::post("/fcm-save",[FcmTokensController::class,"store"]);

    //
    Route::get("/student-behavior",[StudentBehaviorController::class,"get"])->middleware("auth:sanctum");
    Route::get("/student-medical-status",[\App\Http\Controllers\Api\StudentMedicalStatusController::class,"get"])->middleware("auth:sanctum");

});


Route::get("ticketLimit/reset/{user_id}",function ($user_id){

   $user = \App\Models\User::find($user_id);
   $user->ticket_limit = 0;
   $user->save();
   return redirect()->back()->with("status","success");
})->name("ticket.limit.reset");
Route::middleware('auth:sanctum')->group(function (){



//    get('/user', function (Request $request) {
//        return $request->user();
//    });
});
