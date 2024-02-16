<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentMedicalStatusController extends BaseController
{
    //

    public function get(Request $request){

        $user_id = $request->user()->id;
        $studentsIds = [];


        $students = DB::table("students")->where("parents_id", "=", $user_id)->select(["id"])->get();


        foreach ($students as $student){
            $studentsIds[] = $student->id;
        }


        $student_behaviors = DB::table("student_medical_statuses");
        $student_behaviors->whereIn("student_medical_statuses.student_id", $studentsIds);
        $student_behaviors->leftJoin("students","student_medical_statuses.student_id","=","students.id")
            ->select(["student_medical_statuses.desc","student_medical_statuses.status","students.name","student_medical_statuses.created_at"]);
        $student_behaviors->orderBy("student_medical_statuses.id", "desc");


        return $this->sendResponse($student_behaviors->get(),"Success");





    }
}
