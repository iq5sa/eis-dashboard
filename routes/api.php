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

Route::group(["prefix"=>"user"],function (){
    Route::post('/create',[UsersController::class,"create"]);
    Route::post('/login',[UsersController::class,"login"]);
    Route::get('/all',[UsersController::class,"all"])->middleware("auth:sanctum");

});
Route::middleware('auth:sanctum')->group(function (){



//    get('/user', function (Request $request) {
//        return $request->user();
//    });
});
