<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Notification;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class AnnouncementsController extends BaseController
{
    //

    public function getAll(){

        return $this->sendResponse(Notification::all()->take(40),"get Announcements successfully");


    }
}
