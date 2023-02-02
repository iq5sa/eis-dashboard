<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Notification;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementsController extends BaseController
{
    //

    public function getAll(Request $request){

        $grades = [];

        $db = DB::table("students")->where("parents_id","=",$request->user()->id)->select(["level_id"])->get();

        foreach ($db as $grade){
            $grades[] = $grade->level_id;
        }
        $grades[] = 0;

        $notifications = DB::table("notifications")->whereIn("grade_id",$grades)->orderBy("id","DESC")->take(40);


        return $this->sendResponse($notifications->get(),"get Announcements successfully");


    }



}
