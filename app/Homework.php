<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Homework extends Model
{
    public $allow_export_all = true;
    public function getDateAttribute($date)
    {
        return $this->attributes['date'] = Carbon::parse($date)->format('d-m-Y');
    }


    public function save(array $options = [])
    {
        $this->teacher_id = Auth::user()->id;

        parent::save();
    }

    public function scopeTeacher($query){

        if (Auth::user()->role_id ==5){
            return $query->where('teacher_id', '=', Auth::user()->id);

        }else{
            return $query;

        }

    }
}

