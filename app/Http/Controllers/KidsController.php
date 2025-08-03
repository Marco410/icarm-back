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
        try {
            // Validar campos requeridos
            $required_fields = ['user_id', 'nombre', 'a_paterno', 'a_materno', 'fecha_nacimiento', 'sexo'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || !$request->$field) {
                    return $this->badRequest([
                        'status' => 'Error', 
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

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

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'kid' => $kid,
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function get(Request $request){
        try {
            // Validar que user_id esté presente
            if (!$request->has('user_id') || !$request->user_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El user_id es requerido'
                ]);
            }

            // Obtener kids del usuario
            $kids = Kids::where('user_id', $request->user_id)->get()->toArray();

            // Obtener kids donde el usuario es tutor
            $kids_tutor = KidsHasTutor::where('tutor_id', $request->user_id)->with('kid')->get();

            // Combinar los arrays si hay kids donde es tutor
            if ($kids_tutor->isNotEmpty()) {
                foreach ($kids_tutor as $kid_tutor) {
                    if ($kid_tutor->kid) {
                        $kid_data = $kid_tutor->kid->toArray();
                        $kid_data['imtutor'] = true;
                        $kids[] = $kid_data;
                    }
                }
            }

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'kids' => $kids,
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function getTutorsByKid(Request $request){
        try {
            // Validar que kid_id esté presente
            if (!$request->has('kid_id') || !$request->kid_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El kid_id es requerido'
                ]);
            }

            $tutors = KidsHasTutor::where('kid_id', $request->kid_id)->with('user')->get();
      
            return $this->ok([
                'status' => 'Success', 
                'data' => $tutors
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function update(Request $request){
        try {
            // Validar que kid_id esté presente
            if (!$request->has('kid_id') || !$request->kid_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El kid_id es requerido'
                ]);
            }

            // Verificar que el kid existe
            $existing_kid = Kids::find($request->kid_id);
            if (!$existing_kid) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El niño no fue encontrado'
                ]);
            }

            $kid = Kids::where('id', $request->kid_id)->update([ 
                'nombre' => $request->nombre,
                'a_paterno' => $request->a_paterno,
                'a_materno' => $request->a_materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'enfermedad' => $request->enfermedad,
            ]);

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Niño actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function delete(Request $request){
        try {
            // Validar que kid_id esté presente
            if (!$request->has('kid_id') || !$request->kid_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El kid_id es requerido'
                ]);
            }

            // Verificar que el kid existe
            $existing_kid = Kids::find($request->kid_id);
            if (!$existing_kid) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El niño no fue encontrado'
                ]);
            }

            $kid = Kids::where('id', $request->kid_id)->delete();

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Niño eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function generate_code(Request $request){
        try {
            // Validar campos requeridos
            if (!$request->has('kid_id') || !$request->kid_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El kid_id es requerido'
                ]);
            }

            if (!$request->has('code') || !$request->code) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El código es requerido'
                ]);
            }

            if (!$request->has('user_id') || !$request->user_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El user_id es requerido'
                ]);
            }

            // Verificar que el kid existe
            $kid = Kids::where('id', $request->kid_id)->first();
            if (!$kid) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El niño no fue encontrado'
                ]);
            }

            $code = KidsCode::where('kid_id', $kid->id)->first();

            if ($code) {
                $code = KidsCode::where('kid_id', $kid->id)->update([
                    'code' => $request->code,
                    'user_id' => $request->user_id,
                    'is_valid' => 1
                ]);
            } else {
                $code = KidsCode::create([
                    'kid_id' => $request->kid_id,
                    'code' => $request->code,
                    'user_id' => $request->user_id,
                    'is_valid' => 1
                ]);
            }

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Código generado correctamente'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function invalidar_code(Request $request){
        try {
            // Validar que kid_id esté presente
            if (!$request->has('kid_id') || !$request->kid_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El kid_id es requerido'
                ]);
            }

            $code = KidsCode::where('kid_id', $request->kid_id)->first();

            if ($code) {
                $code = KidsCode::where('id', $code->id)->update([
                    'is_valid' => 0
                ]);
            }

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Código invalidado correctamente'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function validar_code(Request $request){
        try {
            // Validar campos requeridos
            if (!$request->has('code') || !$request->code) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El código es requerido'
                ]);
            }

            if (!$request->has('tutor_id') || !$request->tutor_id) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El tutor_id es requerido'
                ]);
            }

            $code = KidsCode::where('code', $request->code)->with(['user', 'kid'])->first();

            if (!$code) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El código no es válido.'
                ]);
            }

            if ($code->is_valid != 1) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El código no es válido. Intenta de nuevo, recuerda que no se debe de cerrar la ventana donde se esté viendo el código.'
                ]);
            }

            $kid_has_tutor = KidsHasTutor::where('kid_id', $code->kid->id)->where('tutor_id', $request->tutor_id)->first();
            $kid_user = Kids::where('user_id', $request->tutor_id)->where('id', $code->kid->id)->first();

            if ($kid_has_tutor || $kid_user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Ya eres tutor de: ' . $code->kid->nombre . ' ' . $code->kid->a_paterno
                ]);
            }

            $tutor = KidsHasTutor::create([
                'kid_id' => $code->kid->id,
                'tutor_id' => $request->tutor_id
            ]);

            $codeU = KidsCode::where('id', $code->id)->update([
                'is_valid' => 0
            ]);

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Éxito. Ahora eres tutor de: ' . $code->kid->nombre . ' ' . $code->kid->a_paterno
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }
}
