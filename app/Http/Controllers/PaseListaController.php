<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\PaseLista;
use App\Models\Maestro;
use App\Models\UserHasMinisterios;


class PaseListaController extends ApiController
{
    public function index(){
        $users = User::with('roles')->first();

        return view('welcome',compact('users'));
    }

    public function getUser(Request $request){


        if($request->evento_id){
            $user = User::where('id',$request->user_id)->with(['maestro_vision','iglesia','roles','pais','sexo','ministerios','pago' => function($q) use($request){
                $q->where('pagos.evento_id', $request->evento_id);
            } ])->where('active',1)->first();
        }else{
            $user = User::where('id',$request->user_id)->with(['maestro_vision','iglesia','roles','pais','sexo','ministerios'])->where('active',1)->first();
        }
        
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
        $dateNow = date("Y-m-d");
        $datetime = date("Y-m-d H:i:s");
        $user = User::where('id',$request->user_id)->first();

        if(!$user){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Usuario no encontrado'
            ]);
        }

        $paseLista = PaseLista::where('id_persona', $user->id)->where('evento_id',$evento->id)->where('created_at','>=',$dateNow)->orderBy('created_at','desc')->first();

        if($paseLista){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'El usuario ya paso lista.'
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

        $user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo','ministerios'])->first();

        $userU = User::where('id', $user->id)->update([ 
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'asignacion' => $request->asignacion,
            'epastores' => $request->epastores,
            'sexo_id' => 0
        ]);

        $maestroUser = Maestro::where('user_id', $user->id)->first();

        if($maestroUser){
            $maestroUpdate = $maestroUser->update([
                'maestro_id' => $request->maestro_id,
            ]);
        }else{
            $maestroUser = Maestro::create([
                'user_id' => $user->id,
                'maestro_id' => $request->maestro_id,
            ]);
        }

        foreach($user->ministerios as $mini){
            $ministerio = UserHasMinisterios::where('user_id', $user->id)->where('ministerio_id',$mini->ministerio->id)->delete();
        }

        foreach($request->ministerios as $min){
            $ministerio = UserHasMinisterios::create([
                'user_id' => $user->id,
                'ministerio_id' => $min
            ]);
        }


        return $this->ok([
            'status' => 'Success', 
            'message' => 'Datos Actualizados con éxito.',
        ]);

    }

 


}
