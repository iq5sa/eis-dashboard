<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
{


    public function parents(){

       return $this->belongsTo(User::class,"parents_id");
    }

    public function grade(){
        return $this->belongsTo(Grade::class,"level_id");
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class,"class_id");
    }



}
