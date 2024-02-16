<?php

namespace App\Widgets;
use App\Ad;
use App\Homework;
use App\Location;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class HomeworkWidget extends BaseDimmer
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
        $count = Homework::count();
        $string = "Homework";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-images',
            'title'  => "{$count} {$string}",
            'text'   => "You have $count Homework in your database.",
            'button' => [
                'text' => "View all homework",
                'link' => route('voyager.homework.index'),
            ],
            'image' => asset("assets/images/homework.jpg")
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
