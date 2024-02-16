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


        $grades = [];
        $classrooms = [];
        //load students
        $students = $request->user()->students;

       foreach ($students as $student){

           $grades[] = $student->grade->id;
           if ($student->classroom !=null){
               $classrooms[] = $student->classroom->id;
           }


       }

       //remove duplicates
        $grades = array_unique($grades);
        $classrooms = array_unique($classrooms);



//        return $classrooms;


       $timetable = DB::table("timetables")
           ->whereIn("grade_id",$grades);
       if ($classrooms !=null || count($classrooms) !=0){
           $timetable->orWhereIn("classroom_id",$classrooms);
       }

       $timetable->join("grades","timetables.grade_id","=","grades.id");
       $timetable->leftJoin("classrooms","timetables.classroom_id","=","classrooms.id");

       $timetable->select(["grades.title as grade_title","classrooms.title as classroom_title","timetables.type","timetables.photo"]);
       return $timetable->first();



//        $timetable_type = $request->input("timetable_type");
//        $grade_id = $request->input("grade_id");
//        $classroom_id = $request->input("classroom_id");
//
//
//        if ($classroom_id =="0"){
//            $classroom_id = null;
//        }
//
//        $timetables = DB::table("timetables")->where([
//           ["type","=",$timetable_type],
//           ["grade_id","=",$grade_id],
//           ["classroom_id","=",$classroom_id]
//        ])->orderBy("id","DESC")->first();
//
//
//
//
//        return $this->sendResponse($timetables,"Get timetable successfully");

    }
}
