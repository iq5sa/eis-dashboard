<?php

namespace App\Http\Controllers\Api;

use App\Homework;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeworkController extends BaseController
{
    //


    public function getHomework(Request $request){
        $grades = [];

        $db = DB::table("students")->where("parents_id","=",$request->user()->id)->select(["level_id"])->get();

        foreach ($db as $grade){
            $grades[] = $grade->level_id;
        }




        $homework = DB::table("homework")->whereIn("homework.grade_id",$grades)
            ->join("grades","homework.grade_id","=","grades.id")
            ->join("subjects","homework.subject_id","=","subjects.id")
            ->select(["homework.id","homework.title","homework.created_at","homework.desc","grades.title as grade_name","subjects.name as subject_name"])
            ->orderBy("homework.id","desc")
        ->take(10);



        return $this->sendResponse($homework->get(),"success");
    }
}
