<?php

namespace App\Http\Controllers;

use App\Models\UserContext;
use App\Models\ViewUserRelationshipWithClient;
use App\Models\User;
use App\Models\FirebaseToken;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class UserController extends ApiController
{

    public $notificationService;

    public function index(Request $request)
    {
        return $this->ok(User::with('iglesia')->get());
    }

    public function getUser(Request $request)
    {

        $user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo'])->first();

        return $this->ok([
            'status' => 'Success', 
            'user' => $user
        ]);

    }


    public function getRoles(Request $request)
    {

        $roles = Role::all();

        return $this->ok([
            'status' => 'Success', 
            'roles' => $roles
        ]);

    }

    public function updateUser(Request $request)
    {

        $u = User::where('email',$request->email)->where('id', '!=', $request->userID)->first();

        if($u){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Ya hay un usuario con este correo. Intenta con otro diferente.'
            ]);
        }

        $user = User::where('id', $request->userID)->with(['iglesia','roles','pais','sexo'])->first();


        $userU = User::where('id', $request->userID)->update([ 
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'sexo_id' => $request->sexo_id,
            'pais_id' => $request->pais_id,
            'iglesia_id' => $request->iglesia_id
        ]);

        foreach($user->roles  as $role){
            $user->removeRole($role->name);
        }

        foreach($request->roles  as $role){
            $user->assignRole($role);
        }

     

        return $this->ok([
            'status' => 'Success', 
            'message' => 'Datos Actualizados con éxito',
            'user' => $user
        ]);

    }



    public function getAll(Request $request)
    {
        if($request->role){
            $users = User::where('email', '!=','')->where(DB::raw("CONCAT(`nombre`, ' ', `apellido_paterno`,' ',`apellido_materno`)"), 'like', '%' . $request->nombre . '%')->with(['iglesia','roles','pais','sexo'])->role($request->role)->get();
        }else{
            $users = User::where('email', '!=','')->where(DB::raw("CONCAT(`nombre`, ' ', `apellido_paterno`,' ',`apellido_materno`)"), 'like', '%' . $request->nombre . '%')->with(['iglesia','roles','pais','sexo'])->get();
        }

        return $this->ok([
            'status' => 'Success', 
            'users' => $users
        ]);

    }

    public function test(Request $request)
    {
       return $request->otra;
    }

    public function sendNotificationToUSer(Request $request){

        $firebase_token = FirebaseToken::where('user_id',$request->user_id)->get();

        $resp = "";
        
        if ($firebase_token) {
            foreach($firebase_token as $token){

                $title = $request->title;
                $body = $request->msg;
                $data = [  
                    'flag' => 'i',
                    'route' => 'kid',
                ];
                
                $notificationService = new NotificationService();
                $response = $notificationService->sendUserNotification($title,$body,$data,$token->token);
                $resp .= $response;
                
            }
            return $this->ok([
                'status' => 'Success', 
                'message' => 'Notificación enviada con éxito.'
            ]);

        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No se puede enviar notificación al usuario por el momento.'
            ]);
        }
    }

    public function updateFotoPerfil(Request $request){

        $user = User::where('id', $request->userID)->first();

        if($user->foto_perfil != null){

            $path = public_path() . '/usuarios/' . $user->id.'/'.$user->foto_perfil;

            unlink($path);
        }

        $nameFoto = $this->storeFoto($request,$user->id,'foto_perfil');

        $userU = User::where('id', $user->id)->update([ 
            'foto_perfil' => $nameFoto
        ]);


        return $this->ok([
            'status' => 'Success', 
            'message' => 'Foto de perfil actualizada con éxito.',
            'nameFoto' => $nameFoto
        ]);
    }

    public function deleteFotoPerfil(Request $request){

        $user = User::where('id', $request->userID)->first();

        if($user->foto_perfil != null){
            $path = public_path() . '/usuarios/' . $user->id.'/'.$user->foto_perfil;
            unlink($path);

            $userU = User::where('id', $user->id)->update([ 
                'foto_perfil' => ""
            ]);
        }

        

        return $this->ok([
            'status' => 'Success', 
            'message' => 'Foto de perfil eliminada con éxito.',
        ]);
    }

    public function storeFoto($request,$id,$nameKey)
    {

        if ($request->hasFile($nameKey)) {
            $file = $request->file($nameKey);
            $nameWithExtension = $file->getClientOriginalName();
            $name = explode('.', $nameWithExtension)[0];
            $nameResult = $this->generateNameFile($name);

           /*  request()->file("imagen")->storeAs('public', 'marcas/' . $nameResult); */

           //$ruta = storage_path() .'/app/public/eventos/'.$request->id;

           $ruta = public_path() . '/usuarios/' . $id;

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

           $path = $ruta ."/".$nameResult;

            Image::make($request->file($nameKey))->encode('jpg', 50)->save($path);

            return $nameResult;
        } else {
            return null;
        }
    }

    public function generateNameFile($value)
    {
        $link = html_entity_decode($value);
        $link = $this->deleteAccents($link);
        $link = strtolower($link); 
        $link = preg_replace("/[^ A-Za-z0-9_.-]/", '', $link);
        $link = str_replace(' ', '-', $link);

        return $link . '.jpg';
    }

    public function deleteAccents($cadena)
    {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
}
