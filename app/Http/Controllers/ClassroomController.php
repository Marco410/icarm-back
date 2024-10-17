<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kids;
use App\Models\Classroom;
use App\Services\NotificationService;

class ClassroomController extends ApiController
{
    public $notificationService;

    public function add(Request $request){


        $kidIn = Classroom::where('kid_id', $request->kid_id)->where('is_in', 1)->first();
        
        if($kidIn){
            $user = User::where('id',$kidIn->user_id)->first();
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'El niño ya esta con el maestro '. $user->nombre . ' ' .$user->apellido_paterno .' en el salón.'
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

        $kids = Classroom::where('user_id',$request->user_id)->where('is_in' , 1)->with('kid')->get();
        return $this->ok([
            'status' => 'Success', 
            'data' => $kids
        ]);
    }

    public function exitFromClass(Request $request){

        $classCheck = Classroom::where('id',$request->class_id)/* ->where('is_in',1) */->update(['is_in' => 0]);
        
        if($classCheck){
            $class = Classroom::where('id',$request->class_id)->first();
            $notificationService = new NotificationService();
            
            $kid = Kids::where('id',$class->kid_id)->with('user')->first();
            $user = User::where('id',$class->user_id)->first();

            $body = $kid->nombre. " ". $kid->a_paterno . " acaba de salir del salón. El maestro: " . $user->nombre. " " . $user->apellido_paterno. " le dio la salida.";

            $data = [  
                'flag' => 'i',
                'route' => 'kid',
                'type' => 'ninos',
            ];
            
            $notificationService->sendNotificationToUserInAPI($kid->user->id,0,"Ministerio de niños",$body,$data);

            return $this->ok([
                'status' => 'Success', 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No se encontró el niño en clase.'
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
