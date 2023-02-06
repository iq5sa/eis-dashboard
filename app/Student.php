<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
{


    public function parents(){

       return $this->belongsTo(User::class,"parents_id");
    }

}
