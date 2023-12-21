<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\PaseLista;


class PaseListaController extends ApiController
{
    public function index(){
        $users = User::with('roles')->first();

        return view('welcome',compact('users'));
    }

    public function getUser(Request $request){
        $user = User::where('id',$request->user_id)->where('active',1)->first();

        if($user){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'user' => $user,
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Hubo un error al encontrar el usuario. Escanea de nuevo.'
            ]);
        }
    }

    public function add(Request $request){

        $user = $request->user_id;

        $evento = Evento::where('id',1)->first();

        $datetime = date("Y-m-d H:i:s");

        $user = User::where('id',$request->user_id)->first();

        if(!$user){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Usuario no encontrado'
            ]);
        }

        $paseLista = PaseLista::create([
            'id_persona' => $user->id,
            'evento' => $evento->nombre,
            'fh' => $datetime
        ]);

        if($paseLista){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'paseLista' => $paseLista,
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
       
    }

 


}
