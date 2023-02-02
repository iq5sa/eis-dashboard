<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends BaseController
{
    //Appointments


    public function store(Request $request){

        $validator = Validator::make($request->all(),[

            "subject"=>["required","max:255"],
            "desc"=>["required"]
        ]);

        if ($validator->fails())
            return $this->sendError("All fields required",$validator->errors());
        else

        $appointment = new Appointment();
        $appointment->subject = $request->subject;
        $appointment->user_id = $request->user()->id;
        $appointment->desc = $request->desc;
        $appointment->save();

        return $this->sendResponse($appointment,"Appointment has been sent");
    }



    public function show(Request $request){


        $appointment = DB::table("appointments")->where("user_id","=",$request->user()->id)->orderBy("id","desc");

        return $this->sendResponse($appointment->get(),"Appointments get successfully");

    }
}
