<?php
namespace App\Utilities;

class NotificationsData{

    public static function reminder(){

        return [
            "title"=>"Homework reminder",
            "body"=>"Please open app to check today’s homework.",
            "type"=>"reminder"
        ];
    }

    public static function appointment($date){

        return [
            "title"=>"تم تحديد الموعد",
            "body"=>"بتاريخ: " . $date["date"] . " الساعة " . $date["hour"],
            "type"=>"appointment"
        ];
    }


    public static function ticket($reply){

        return [
            "title"=>"تمت الاجابة على تذكرتك",
            "body"=>$reply,
            "type"=>"ticket"
        ];
    }

    public static function custom($data){

        return [
            "title"=>$data["title"],
            "body"=>$data["body"],
            "type"=>"announcements"
        ];
    }


}
