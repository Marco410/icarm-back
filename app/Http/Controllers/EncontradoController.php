<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Iglesia;
use App\Models\Encontrado;
use App\Models\Evento;
use App\Services\NotificationService;

class EncontradoController extends ApiController
{
    public $notificationService;

    public function getAll(Request $request){


        $iglesias = Iglesia::get();
        
        if($iglesias){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'iglesias' => $iglesias
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

    public function store(Request $request){

        $evento = Evento::where('id', $request->evento_id)->first();


        if($request->user_id){
            $encontrado = Encontrado::where('user_id',$request->user_id)->first();
            if($encontrado){
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Ya estás registrado al evento ' . $evento->nombre . "."
                ]);
            }
        }


        $encontrado = Encontrado::where('evento_id',$request->evento_id)->where('nombre',$request->nombre)->where('a_paterno',$request->a_paterno)->where('a_materno',$request->a_materno)->where('telefono',$request->telefono)->first();

        if($encontrado){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Parece que ' . $encontrado->nombre . " ya esta registrado al evento " . $evento->nombre . "."
            ]);
        }


        $encontrado = Encontrado::create([
            'user_id' => $request->user_id,
            'user_invited_id' => $request->user_invited_id,
            'evento_id' => $request->evento_id,
            'nombre' => $request->nombre,
            'a_paterno' => $request->a_paterno,
            'a_materno' => $request->a_materno,
            'email' => $request->email,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'estado_civil' => $request->estado_civil,
            'telefono' => $request->telefono,
            'ref_nombre' => $request->ref_nombre,
            'ref_telefono' => $request->ref_telefono,
        ]);


        if($encontrado){
            return $this->ok([
                'status' => 'Success', 
                'data' => $encontrado
            ]);
        }else{

            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

    public function getInvites(Request $request){


        $invitados = Encontrado::where('user_invited_id',$request->user_id)->where('evento_id',$request->evento_id)->get();
        
        if($invitados){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'invitados' => $invitados
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

    public function getEncontrado(Request $request){


        $encontrado = Encontrado::where('id',$request->encontrado_id)->first();
        
        if($encontrado){
            return $this->ok([
                'status' => 'Success', 
                'data' => $encontrado
                
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

}
