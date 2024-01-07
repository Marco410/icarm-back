<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kids;
use App\Models\Classroom;



class ClassroomController extends ApiController
{

    public function add(Request $request){


        $kidIn = Classroom::where('kid_id', $request->kid_id)->where('is_in', 1)->first();
        $user = User::where('id',$request->user_id)->first();

        if($kidIn){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'El niño ya esta con el maestro '. $user->nombre . ' ' .$user->apellido_paterno .' en el salón'
            ]);
        }

        $kid = Classroom::create([ 
            'user_id' => $request->user_id,
            'kid_id' => $request->kid_id,
            'is_in' => 1,
        ]);

        if($kid){
            return $this->ok([
                'status' => 'Success', 
                'data' => []
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
       
    }

    public function getKidsFromTeacher(Request $request){

        $kids = Classroom::where('user_id' , $request->user_id)->where('is_in' , 1)->with('kid')->get();
        return $this->ok([
            'status' => 'Success', 
            'data' => $kids
        ]);
    }

    public function exitFromClass(Request $request){

        $kid = Classroom::where('id',$request->class_id)->update(['is_in' => 0]);

        if($kid){
            return $this->ok([
                'status' => 'Success', 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }


    public function getTeachers(Request $request){
        $users = User::role('Maestro Niños')->with('classroom')->get(); 

        if($users){
            return $this->ok([
                'status' => 'Success', 
                'teachers' => $users
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }


 

}
