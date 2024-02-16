<?php

namespace App\Http\Controllers\Api;

use App\FcmTokens;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Models\Role;

class UsersController extends BaseController
{


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
            $students = $user->load(["students.grade","students.classroom"]);


            $success['token'] =  $user->createToken('eisToken')->plainTextToken;
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['role'] =  Role::find($user->role_id)->name;
            $success['email'] =  $user->email;
            $success['avatar'] =  $user->avatar;
            $success['login_code'] =  $user->login_code;
            $success['en_name'] =  $user->en_name;
            $success["user"] = $students;

            $user->save();

            if ($success["id"] !="" || $success["id"] !=null){
                $tokens = FcmTokens::where("fcm_token","=",$request->fcm_token);
                if($tokens->count() ==0) {
                    $fcmTokens = new FcmTokens();
                    $fcmTokens->fcm_token = $request->fcm_token;
                    $fcmTokens->parents_id = $success["id"];
                    $fcmTokens->save();

                }
            }



            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function all(){

        return User::all();

    }

    public function profile(Request $request){



        $students = DB::table("students")->where("parents_id","=",$request->user()->id)
        ->join("grades","students.level_id","=","grades.id")
            ->leftJoin("classrooms","students.class_id","=","classrooms.id")
        ->select(["students.id","students.name","students.sn_number","students.photo","grades.title as grade","classrooms.title as classroom","students.en_name"])->get();




        return $this->sendResponse(["user"=>$request->user(),"students"=>$students],"success");
//        return ["userData"=>$user,"students"=>$students];
    }

    public function getStudentAcademy(Request $request){

        $parent_id = $request->user()->id;
        $grades = [];
        $classrooms = [];

        $findStudents = DB::table("students")->where("parents_id",$parent_id)
            ->join("grades","students.level_id","=","grades.id")
            ->leftJoin("classrooms","students.class_id","=","classrooms.id")
            ->select(["grades.title as grade_title","grades.id as grade_id","classrooms.title as classroom_title","classrooms.id as classroom_id"])
            ->get();

        foreach ($findStudents as $student){
            $grades[] = [
                "id"=>$student->grade_id,
                "title"=>$student->grade_title,
            ];

            $classrooms[] = [
                "id"=>$student->classroom_id,
                "title"=>$student->classroom_title,
            ];
        }


        return response()->json(["grades"=>$grades,"classrooms"=>$classrooms]);

    }
}
