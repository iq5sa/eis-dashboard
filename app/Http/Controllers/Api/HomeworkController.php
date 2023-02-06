<?php

namespace App\Http\Controllers\Api;

use App\Homework;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HomeworkController extends BaseController
{
    //


    public function getHomework(Request $request)
    {
        $grades = [];
        $classrooms = [];

        $db = DB::table("students")->where("parents_id", "=", $request->user()->id)->select(["level_id", "class_id"])->get();

        foreach ($db as $grade) {
            $grades[] = $grade->level_id;

            $classrooms[] = $grade->class_id;
        }


//        UPDATE homework set classroom_id=0 where classroom_id IS NULL



        $homework = DB::table("homework");
        $homework->whereIn("homework.grade_id", $grades);
        $homework->whereIn("homework.classroom_id",$classrooms);





        $homework->join("grades", "homework.grade_id", "=", "grades.id");
        $homework->join("subjects", "homework.subject_id", "=", "subjects.id");
        $homework->leftJoin("classrooms", "homework.classroom_id", "=", "classrooms.id");
        $homework->select(["homework.id", "homework.title", "homework.created_at", "homework.desc", "grades.title as grade_name", "classrooms.title as classroom_title", "subjects.name as subject_name"]);
        $homework->orderBy("homework.id", "desc");
        $homework->take(50);


        return $this->sendResponse($homework->get(), "success");
    }
}
