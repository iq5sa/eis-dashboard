<?php

namespace App\Http\Controllers\Api;

use App\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivitiesController extends BaseController
{
    //


    public function show(){

        return $this->sendResponse(Activity::orderBy("id","DESC")->take(20)->get(),"get news success");
    }
}
