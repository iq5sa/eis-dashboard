<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlidersController extends BaseController
{
    //

    public function show(){

        $sliders = DB::table("sliders")->orderBy("id","desc")->take(10)->get();
        return $this->sendResponse($sliders,"Sliders get successfully");
    }
}
