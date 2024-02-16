<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{

    protected $casts = [
        'created_at' => "datetime:Y-m-d",
    ];


}
