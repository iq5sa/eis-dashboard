<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\FcmTokens;
use Dotenv\Validator;
use Illuminate\Http\Request;

class FcmTokensController extends BaseController
{
    //

    public function store(Request $request){

        return $this->sendResponse("token exist","saved success");

        
//        $validator = \Illuminate\Support\Facades\Validator::make($request->all(),["fcm_token"=>"required"]);
//
//        if ($validator->fails() || empty($request->fcm_token)){
//            return $this->sendError("fcm_token can not be empty",$validator->errors());
//        }
//
//        $tokens = FcmTokens::where("fcm_token","=",$request->fcm_token);
//        if($tokens->count() ==0){
//            $token = new FcmTokens();
//            $token->fcm_token = $request->fcm_token;
//            $token->save();
//            return $this->sendResponse($token,"saved success");
//
//        }else{
//            return $this->sendResponse("token exist","saved success");
//
//        }



    }
}
