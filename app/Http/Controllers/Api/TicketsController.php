<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketsController extends BaseController
{
    //Tickets

    public function store(Request $request){


        $validate = Validator::make($request->all(),[
            "title"=>["required","max:255"],
            "department"=>["required","max:255"],
            "desc"=>["required","max:500"],
        ]);

        if ($validate->fails())
            return $this->sendError("All fields required",$validate->errors());
        else

        $user = User::find($request->user_id);
        if ($user !=null && $user->ticket_limit < 10){
            $ticket = new Ticket();
            $ticket->title = $request->title;
            $ticket->department = $request->department;
            $ticket->desc = $request->desc;
            $ticket->user_id = $request->user()->id;
            $ticket->save();

            $user->ticket_limit = $user->ticket_limit + 1;
            $user->save();

            return $this->sendResponse($ticket,"ticket has been added");
        }else{
            return  $this->sendError("You reached the rate limit of your tickets in this month");
        }




    }


    public function getTickets(Request $request){

        $tickets = DB::table("tickets")->where("user_id","=",$request->user()->id)->orderBy("id","desc")->take(10)->get();

        return $this->sendResponse(["tickets"=>$tickets,"ticketCount"=>$request->user()->ticket_limit],"get ticket successfully");

    }
}
