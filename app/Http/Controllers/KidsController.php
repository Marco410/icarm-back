<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kids;
use App\Models\KidsCode;
use App\Models\KidsHasTutor;


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

        $kids = Kids::where('user_id',$request->user_id)->get()->toArray();

        $kids_tutor = KidsHasTutor::where('tutor_id',$request->user_id)->with('kid')->get();

        if($kids_tutor){
            if (is_array($kids)) {
                foreach ($kids_tutor as $kid) {
                    $kid->kid['imtutor'] = true;
                    $cellid =  array_push($kids, $kid->kid);
                }
            }
        }

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

    public function getTutorsByKid(Request $request){

        $tutors = KidsHasTutor::where('kid_id',$request->kid_id)->with('user')->get();
      
        if($tutors){
            return $this->ok([
                'status' => 'Success', 
                'data' => $tutors
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


    public function generate_code(Request $request){

        $kid = Kids::where('id',$request->kid_id)->first();
        $code = KidsCode::where('kid_id',$kid->id)->first();

        if($code){
            $code = KidsCode::where('kid_id',$kid->id)->update([
                'code' => $request->code,
                'user_id' => $request->user_id,
                'is_valid' => 1
            ]);
        }else{
            $code = KidsCode::create([
                'kid_id' => $request->kid_id,
                'code' => $request->code,
                'user_id' => $request->user_id,
                'is_valid' => 1
            ]);
        }

        if($code){
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

    public function invalidar_code(Request $request){
        $code = KidsCode::where('kid_id',$request->kid_id)->first();

        if($code){
            $code = KidsCode::where('id',$code->id)->update([
                'is_valid' => 0
            ]);
        }

        if($code){
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

    public function validar_code(Request $request){

        $code = KidsCode::where('code',$request->code)->with(['user','kid'])->first();


        if($code){
            if($code->is_valid == 1){
                    $kid_has_tutor = KidsHasTutor::where('kid_id',$code->kid->id)->where('tutor_id',$request->tutor_id)->first();

                    $kid_user = Kids::where('user_id',$request->tutor_id)->where('id',$code->kid->id)->first();

                    if($kid_has_tutor || $kid_user){
                       
                        return $this->badRequest([
                            'status' => 'Error', 
                            'message' => 'Ya eres tutor de: '. $code->kid->nombre . ' ' . $code->kid->a_paterno
                        ]);
                    }else{
                        $tutor = KidsHasTutor::create([
                            'kid_id' => $code->kid->id,
                            'tutor_id' => $request->tutor_id
                        ]);

                        $codeU = KidsCode::where('id',$code->id)->update([
                            'is_valid' => 0
                        ]);

                        return $this->ok([
                            'status' => 'Success', 
                            'message' => 'Éxito. Ahora eres tutor de: '. $code->kid->nombre . ' ' . $code->kid->a_paterno
                        ]);
                    }
            
            
            }else{
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El código no es válido. Intenta de nuevo, recuerda que no se debe de cerrar la ventana donde se esté viendo el código.'
                ]);
            }
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'El código no es válido.'
            ]);
        }
    }
}
