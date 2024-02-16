<?php

namespace App\Jobs;

use App\Utilities\FirebaseFcm;
use App\Utilities\NotificationsData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FcmNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        //
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $params = $this->params;

        if ($params["parent_id"] != null){
            $firebaseFcm = new firebasefcm();
            $firebaseFcm->pushForSingleUser($params["parent_id"],NotificationsData::custom(["title"=>$params["notification"]["title"],"body"=>$params["notification"]["body"]]));
        }elseif ($params["grade_id"] !=null){
            $firebaseFcm = new firebasefcm();
            $firebaseFcm->pushForGrade($params["grade_id"],NotificationsData::custom(["title"=>$params["notification"]["title"],"body"=>$params["notification"]["body"]]));

        }else{
            $firebaseFcm = new firebasefcm();
            $firebaseFcm->pushForAll(NotificationsData::custom(["title"=>$params["notification"]["title"],"body"=>$params["notification"]["body"]]));

        }
    }
}
