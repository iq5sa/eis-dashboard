<?php

use App\Imports\UsersImport;
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
//Route::get('/excel',function (){
//     Excel::import(new UsersImport, 'users.xlsx');
//
//
//});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
