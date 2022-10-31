<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends BaseController
{
    //Appointments


    public function store(Request $request){

        $validator = Validator::make($request->all(),[

            "subject"=>["required","max:255"],
            "with"=>["required","max:255"],
            "user_id"=>["required","max:255"],
        ]);

        if ($validator->fails())
            return $this->sendError("All fields required",$validator->errors());
        else

        $appointment = new Appointment();
        $appointment->subject = $request->subject;
        $appointment->with = $request->with;
        $appointment->user_id = $request->user_id;
        $appointment->save();

        return $this->sendResponse($appointment,"Appointment has been sent");
    }
}
