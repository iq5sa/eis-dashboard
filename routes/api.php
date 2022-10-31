<?php

use App\Http\Controllers\Api\AnnouncementsController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\TicketsController;
use App\Http\Controllers\Api\UsersController;
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
        Route::get("/profile/{id}","profile")->middleware("auth:sanctum");
    });

    //Homework

    Route::get("/homework",[HomeworkController::class,"getHomework"])->middleware("auth:sanctum");

    //Tickets
    Route::post("/ticket/submit",[TicketsController::class,"store"])->middleware("auth:sanctum");
    Route::get("/tickets/user/{user_id}",[TicketsController::class,"getTickets"])->middleware("auth:sanctum");


    //book appointment
    Route::post("/appointment/book",[AppointmentController::class,"store"])->middleware("auth:sanctum");

    //Announcements
    Route::get("/announcements",[AnnouncementsController::class,"getAll"])->middleware("auth:sanctum");

});
Route::middleware('auth:sanctum')->group(function (){



//    get('/user', function (Request $request) {
//        return $request->user();
//    });
});
