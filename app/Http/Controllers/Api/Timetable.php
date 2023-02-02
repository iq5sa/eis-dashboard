<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Timetable extends BaseController
{
    //

    public function show(Request $request){

        $timetable_type = $request->input("timetable_type");
        $grade_id = $request->input("grade_id");
        $classroom_id = $request->input("classroom_id");

        $timetables = DB::table("timetables")->where([
           ["type","=",$timetable_type],
           ["grade_id","=",$grade_id]
        ]);

        if ($classroom_id !=0 || !empty($classroom_id)){
            $timetables->where("classroom_id","=",$classroom_id);
        }

        $timetables->orderBy("id","DESC")->first();

        return $this->sendResponse($timetables,"Get timetable successfully");

    }
}
