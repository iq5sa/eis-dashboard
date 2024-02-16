<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\FcmTokens;
use Dotenv\Validator;
use Illuminate\Http\Request;

class FcmTokensController extends BaseController
{
    //

    public function store(Request $request)
    {

        return $this->sendResponse("token exist", "saved success");


    }

}
