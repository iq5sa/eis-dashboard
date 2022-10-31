<?php

namespace App\Imports;

use App\Models\User;
use App\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToCollection
{


    public function collection(Collection $collection)
    {
        // TODO: Implement collection() method.



        foreach ($collection as $row){

            $sn_number = $row[0];
            $first_name = $row[1];
            $father_name = $row[2];
            $last_name = $row[3];
            $student_ar_fullname = $row[4];
            $grad = $row[5];

            $users = User::create([
                "role_id"=>3,
                "name"=>$father_name . " " . $last_name,
                "login_code"=>"eis". random_int(100000, 999999),
                "password"=>Hash::make("11223344"),

            ]);


            $student  = new Student();
            $student->name = $student_ar_fullname;
            $student->parents_id = $users->id;
            $student->level_id = $grad;
            $student->sn_number = $sn_number;
            $student->save();

        }
    }
}
