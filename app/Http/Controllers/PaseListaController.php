<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\PaseLista;
use App\Models\Maestro;


class PaseListaController extends ApiController
{
    public function index(){
        $users = User::with('roles')->first();

        return view('welcome',compact('users'));
    }

    public function getUser(Request $request){
        $user = User::where('id',$request->user_id)->with(['maestro_vision','iglesia','roles','pais','sexo'])->where('active',1)->first();

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

        $evento = Evento::where('id',$request->evento_id)->first();

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
            'evento_id' => $evento->id,
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


    public function updateUser(Request $request)
    {

        $userU = User::where('id', $request->userID)->update([ 
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'asignacion' => $request->asignacion,
            'sexo_id' => 0
        ]);

        $maestroUser = Maestro::where('user_id', $request->userID)->first();

        if($maestroUser){
            $maestroUpdate = $maestroUser->update([
                'maestro_id' => $request->maestro_id,
            ]);
        }else{
            $maestroUser = Maestro::create([
                'user_id' => $request->userID,
                'maestro_id' => $request->maestro_id,
            ]);
        }


        return $this->ok([
            'status' => 'Success', 
            'message' => 'Datos Actualizados con éxito.',
        ]);

    }

 


}
