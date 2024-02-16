<?php

namespace App\Widgets;
use App\Ad;
use App\Appointment;
use App\Homework;
use App\Location;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class AppointmentsWidget extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Appointment::count();
        $string = "Appointments";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-images',
            'title'  => "{$count} {$string}",
            'text'   => "You have $count Appointments in your database.",
            'button' => [
                'text' => "View all appointments",
                'link' => route('voyager.appointments.index'),
            ],
            'image' => asset("assets/images/appontments.jpg")
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Voyager::model('User'));
    }
}
