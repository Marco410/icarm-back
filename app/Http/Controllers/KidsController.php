<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kids;


class KidsController extends ApiController
{
    public function index(){
        $users = User::with('roles')->first();

        return view('welcome',compact('users'));
    }

    public function add(Request $request){

        $kid = Kids::create([ 
            'user_id' => $request->user_id,
            'nombre' => $request->nombre,
            'a_paterno' => $request->a_paterno,
            'a_materno' => $request->a_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'enfermedad' => $request->enfermedad,
            'active' => 1
        ]);
        if($kid){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'kid' => $kid,
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
       
    }


    public function get(Request $request){

        $kids = Kids::where('user_id',$request->user_id)->get();
       
        if($kids){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'kids' => $kids,
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }


    public function update(Request $request){

        $kid = Kids::where('id',$request->kid_id)->update([ 
            'nombre' => $request->nombre,
            'a_paterno' => $request->a_paterno,
            'a_materno' => $request->a_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'enfermedad' => $request->enfermedad,
        ]);

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

    public function delete(Request $request){

        $kid = Kids::where('id',$request->kid_id)->delete();

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
}
