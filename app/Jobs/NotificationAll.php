<?php

namespace App\Jobs;

use App\Models\FcmTokens;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class NotificationAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

//    public $title;
//    public $desc;
    public function __construct()
    {
        //

//        $this->title = $title;
//        $this->desc = $desc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FcmTokens $fcm)
    {
        //



        $fcm = new FcmTokens();
        $fcm->fcm_token = "ali";
        $fcm->save();


//        $parents = \App\Models\User::where("role_id","=",3)->get();

//        foreach ($parents as $parent){
//            $response = Http::withHeaders([
//
//                "Content-Type" => "application/json",
//                "Authorization" => "key=AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv"
//            ])->post('https://fcm.googleapis.com/fcm/send', [
//                'to' => $parent->fcm_token,
//                "priority" => "high",
//                "notification" => [
//                    "title" => $this->title,
//                    "body" => $this->desc,
//                    "sound" => "default"
//                ]
//            ]);
//        }


    }
}
