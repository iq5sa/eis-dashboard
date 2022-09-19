<?php

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
    });

});
Route::middleware('auth:sanctum')->group(function (){



//    get('/user', function (Request $request) {
//        return $request->user();
//    });
});
