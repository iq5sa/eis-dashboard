<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentBehaviorController extends BaseController
{
    //


    public function get(Request $request){

         $user_id = $request->user()->id;
         $studentsIds = [];


        $students = DB::table("students")->where("parents_id", "=", $user_id)->select(["id"])->get();


        foreach ($students as $student){
            $studentsIds[] = $student->id;
        }


        $student_behaviors = DB::table("student_behaviors");
        $student_behaviors->whereIn("student_behaviors.student_id", $studentsIds);
        $student_behaviors->leftJoin("students","student_behaviors.student_id","=","students.id")
        ->select(["student_behaviors.desc","students.name","student_behaviors.created_at"]);
        $student_behaviors->orderBy("student_behaviors.id", "desc");



        return $this->sendResponse($student_behaviors->get(),"Success");





    }
}
