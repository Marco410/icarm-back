<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ministerio;
use App\Models\UserHasMinisterios;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use Intervention\Image\Facades\Image;


class UserController extends ApiController
{

    public $notificationService;

    public function index(Request $request)
    {
        return $this->ok(User::with('iglesia')->get());
    }

    public function getUser(Request $request){
        try {
            if (!$request->has('userID') || !$request->userID) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El userID es requerido'
                ]);
            }

            $user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo','ministerios', 'ownChurch'])->first();
            
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Usuario no encontrado'
                ]);
            }

            $user->apellido_materno = $user->apellido_materno ?? '';
            $user->telefono = $user->telefono ?? '';
            $user->fecha_nacimiento = $user->fecha_nacimiento ?? '';
            $user->foto_perfil = $user->foto_perfil ?? '';

            return $this->ok([
                'status' => 'Success', 
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function getRoles(Request $request){
        try {
            $roles = Role::all();
            return $this->ok([
                'status' => 'Success', 
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos obtener los roles, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function getMinisterios(Request $request){
        try {
            $ministerios = Ministerio::get();
            return $this->ok([
                'status' => 'Success', 
                'ministerios' => $ministerios
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos obtener los ministerios, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            // Validar campos requeridos
            $required_fields = ['userID', 'nombre', 'apellido_paterno', 'email'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || !$request->$field) {
                    return $this->badRequest([
                        'status' => 'Error', 
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            // Verificar que el usuario existe
            $existing_user = User::find($request->userID);
            if (!$existing_user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Usuario no encontrado'
                ]);
            }

            // Verificar que el email no esté duplicado
            $u = User::where('email', $request->email)->where('id', '!=', $request->userID)->first();
            if ($u) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Ya hay un usuario con este correo. Intenta con otro diferente.'
                ]);
            }

            $user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo','ministerios', 'ownChurch'])->first();

            if ($request->has('roles') && is_array($request->roles)) {
                foreach($user->roles as $role){
                    $user->removeRole($role->name);
                }

                foreach($request->roles as $role){
                    if ($role == 'Pastor' && $request->own_church == null) {
                        return $this->badRequest([
                            'status' => 'Error', 
                            'message' => 'Seleccione una iglesia para agregar al pastor.'
                        ]);
                    }
                    $user->assignRole($role);
                }
            }

            $updateData = [
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno ?? '',
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'email' => $request->email,
                'telefono' => $request->telefono ?? '',
                'sexo_id' => $request->sexo_id,
                'pais_id' => $request->pais_id,
                'iglesia_id' => $request->iglesia_id,
                'own_church' => $request->own_church ?? null
            ];

            $userU = User::where('id', $request->userID)->update($updateData);

            if ($request->has('ministerios') && is_array($request->ministerios)) {
                foreach($user->ministerios as $mini){
                    $ministerio = UserHasMinisterios::where('user_id', $user->id)->where('ministerio_id',$mini->ministerio->id)->delete();
                }

                foreach($request->ministerios as $min){
                    $ministerio = UserHasMinisterios::create([
                        'user_id' => $user->id,
                        'ministerio_id' => $min
                    ]);
                }
            }

            $updated_user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo','ministerios'])->first();

            if ($updated_user) {
                $updated_user->apellido_materno = $updated_user->apellido_materno ?? '';
                $updated_user->telefono = $updated_user->telefono ?? '';
                $updated_user->fecha_nacimiento = $updated_user->fecha_nacimiento ?? '';
                $updated_user->foto_perfil = $updated_user->foto_perfil ?? '';
                $updated_user->own_church = $updated_user->own_church ?? null;
            }

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Datos Actualizados con éxito',
                'user' => $updated_user
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }



    public function getAll(Request $request)
    {
        try {
            $query = User::with(['iglesia','roles','pais','sexo','ministerios', 'ownChurch']);
            
            if ($request->has('nombre') && !empty($request->nombre)) {
                $partes = explode(' ', $request->nombre);
            
                $query->where(function ($q) use ($partes) {
                    foreach ($partes as $parte) {
                        $q->where(function($subq) use ($parte) {
                            $subq->orWhere('nombre', 'like', "%$parte%")
                                 ->orWhere('apellido_paterno', 'like', "%$parte%")
                                 ->orWhere('apellido_materno', 'like', "%$parte%");
                        });
                    }
                });
            }
            
            if ($request->has('role') && !empty($request->role)) {
                $query->role($request->role);
            }
            
            $users = $query->get();

            foreach ($users as $user) {
                $user->apellido_materno = $user->apellido_materno ?? '';
                $user->telefono = $user->telefono ?? '';
                $user->fecha_nacimiento = $user->fecha_nacimiento ?? '';
                $user->foto_perfil = $user->foto_perfil ?? '';
            }

            return $this->ok([
                'status' => 'Success', 
                'users' => $users
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos obtener los usuarios, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function test(Request $request)
    {
       return $request->otra;
    }

    public function sendNotificationToUSer(Request $request){
        try {
            // Validar campos requeridos
            $required_fields = ['user_id', 'title', 'msg'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || !$request->$field) {
                    return $this->badRequest([
                        'status' => 'Error', 
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Usuario no encontrado'
                ]);
            }

            $notificationService = new NotificationService();
            $data["type"] = "notification";

            $notificationService->sendNotificationToUserInAPI($request->user_id, 0, $request->title, $request->msg, $data);

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Notificación enviada con éxito.'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos enviar la notificación, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function updateFotoPerfil(Request $request)  {
        try {
            if (!$request->has('userID') || !$request->userID) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El userID es requerido'
                ]);
            }

            $user = User::where('id', $request->userID)->first();
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Usuario no encontrado'
                ]);
            }

            if (!$request->hasFile('foto_perfil')) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Debe seleccionar una imagen'
                ]);
            }

            if ($user->foto_perfil != null) {
                $path = public_path() . '/usuarios/' . $user->foto_perfil;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $nameFoto = $this->storeFoto($request, $user->id, 'foto_perfil');
            
            if (!$nameFoto) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'No se pudo procesar la imagen'
                ]);
            }

            $userU = User::where('id', $user->id)->update([ 
                'foto_perfil' => $nameFoto
            ]);

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Foto de perfil actualizada con éxito.',
                'nameFoto' => $nameFoto
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos actualizar la foto de perfil, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function deleteFotoPerfil(Request $request){
        try {
            if (!$request->has('userID') || !$request->userID) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El userID es requerido'
                ]);
            }

            $user = User::where('id', $request->userID)->first();
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'Usuario no encontrado'
                ]);
            }

            if ($user->foto_perfil == null) {
                return $this->badRequest([
                    'status' => 'Error', 
                    'message' => 'El usuario no tiene foto de perfil'
                ]);
            }

            $path = public_path() . '/usuarios/' . $user->foto_perfil;
            
            if (file_exists($path)) {
                unlink($path);
            }

            $userU = User::where('id', $user->id)->update([ 
                'foto_perfil' => null
            ]);

            return $this->ok([
                'status' => 'Success', 
                'message' => 'Foto de perfil eliminada con éxito.',
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos eliminar la foto de perfil, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function storeFoto($request, $id, $nameKey)
    {
        try {
            if (!$request->hasFile($nameKey)) {
                return null;
            }

            $file = $request->file($nameKey);
            
            if (!$file->isValid()) {
                return null;
            }

            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowed_types)) {
                return null;
            }

            $name = $id . ".jpg";
            $ruta = public_path() . '/usuarios';

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

            $path = $ruta . "/" . $name;

            Image::make($request->file($nameKey))->encode('jpg', 50)->save($path);

            return $name;

        } catch (\Exception $e) {
            return null;
        }
    }
}
