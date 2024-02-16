<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Classroom extends Model
{
    public function timetables(){
        return $this->hasMany(Timetable::class,"classroom_id");
    }
}
