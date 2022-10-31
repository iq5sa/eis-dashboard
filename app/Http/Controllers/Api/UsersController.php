<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Models\Role;

class UsersController extends BaseController
{
    //

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('eisToken')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }


    public function login(Request $request){
        if(Auth::attempt(['login_code' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('eisToken')->plainTextToken;
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['role'] =  Role::find($user->role_id)->name;
            $success['email'] =  $user->email;
            $success['avatar'] =  $user->avatar;


            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function all(){

        return User::all();

    }

    public function profile($id){
        $id = intval($id);
        $user = User::find($id);


        $students = DB::table("students")->where("parents_id","=",$id)
        ->join("grades","students.level_id","=","grades.id")
        ->select(["students.id","students.name","students.sn_number","students.photo","grades.title as grade"])->get();




        return $this->sendResponse(["user"=>$user,"students"=>$students],"success");
//        return ["userData"=>$user,"students"=>$students];
    }
}
