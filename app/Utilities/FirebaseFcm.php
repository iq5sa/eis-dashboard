<?php
namespace App\Utilities;
use App\Models\FcmTokens;
use App\NotificationsLog;
use Illuminate\Support\Facades\Http;

class FirebaseFcm{

    private $firebaseAuthKey = "AAAAyk-VJjQ:APA91bHQA9AjI7d9n59swNEK-T74R1bv2UyUdzI8rLTOIW1PFENwys7xuVvI7bnhMqxtGKOVehfS-V3YROI3hpjMFWjA-uslieEFyPhi-3Vw7KHgMpgiAH95GiATvPfJ9FQ7Z3D3zDpv";
    private $firebaseHost = "https://fcm.googleapis.com/fcm/send";


    public function config($token,$data = []){
        $http = Http::withHeaders([

            "Content-Type" => "application/json",
            "Authorization" => "key=".$this->firebaseAuthKey
        ])->post($this->firebaseHost, [
            'to' => $token,
            "priority" => "high",
            "notification" => [
                "title" => $data["title"],
                "body" => $data["body"],
                "sound" => "default"
            ],
            "data"=>[
                "type"=>$data["type"]
            ]
        ]);


//        $log = new NotificationsLog();
//        $log->log = $http;
//        $log->save();

        return $http;
    }


    public function pushForAll($data){

        $tokens = FcmTokens::all();

        foreach ($tokens as $token){
            $this->config($token,$data);
        }
    }


    public function pushForSingleUser($id,$data){
        $tokens = FcmTokens::where("parents_id","=",$id)->get();

        foreach ($tokens as $token){
            $this->config($token->fcm_token,$data);
        }
    }

    public function pushForGrade($id,$data){
        $students = \App\Student::where("level_id","=",$id)->get();

        $uniqueStudents = $students->unique("parents_id");


        foreach ($uniqueStudents as $student){
            $parent_id = $student->parents->id;
            $tokens = FcmTokens::where("parents_id","=",$parent_id)->get();


            foreach ($tokens as $token){
                $this->config($token->fcm_token,$data);
            }
        }
    }



}
