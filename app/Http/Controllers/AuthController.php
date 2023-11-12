<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FirebaseToken;
use App\Models\UserSession;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
       
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    #comentario
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = new User();
        $user = $user->where('email', $request->email)->first();
        
        if (empty($user)) {
            return [
                'status' => 'Error',
                'message' => 'Usuario no encontrado'
            ];
        }

        if ($user->password != hash('sha512', $request->password)) {
            return $this->forbidden('Contraseña incorrecta', 403.01);
        }

        $customClaims = ['custom' => [/*'user' => $user*/]];

        $token = JWTAuth::claims($customClaims)->fromUser($user);

        $newLogin = true;

        if ($request->autologin) {

            $lastSession = UserSession::find($user->id);

            if (!empty($lastSession)) {
                if (Carbon::parse($lastSession->last_login)->addDays(env('LOGIN_LIFETIME')) > Carbon::now()) {
                    $newLogin = false;
                    $userSession = UserSession::refreshToken($user->id, $token);
                } else {
                    return $this->conflict('Autologin expiró', 409.01);
                }
            }
        }

        $user->roles;

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }


    public function payload()
    {
        return response()->json(JWTAuth::payload()->toArray());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // $user = $this->getUserLoggedIn();
        UserSession::clearData($this->user->id);
        auth()->logout();
        return $this->ok(true);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->ok(JWTAuth::refresh(true, true));
    }

    public function register(Request $request){

        $nombre = $request->nombre;
        $apellidos = $request->apellidos;
        $email = $request->email;
        $password = hash('sha512', $request->password);


        $u = User::where('email',$email)->first();

        if($u){
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'Ya hay un usuario con este correo. Intenta iniciar sesión'
            ]);
        }


        $user = User::create([ 
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $email,
            'password' => $password,
            'telefono' => $request->telefono,
            'sexo' => $request->sexo,
            'pais_id' => $request->pais_id,
            'active' => 1
        ])->assignRole('Usuario');

        $customClaims = ['custom' => [/*'user' => $user*/]];

        $token = JWTAuth::claims($customClaims)->fromUser($user);
        $newLogin = true;

        return $this->ok([
            'status' => 'Success',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }

    public function update(Request $request){

        $user = User::where('id', $request->userId)->update([ 
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'sexo' => $request->sexo,
            'pais_id' => $request->pais_id,
            'active' => 1
        ]);

        if($request->password){
            $password = hash('sha512', $request->password);
            $userPass = User::where('id', $request->userId)->update([ 
                'password' => $password,
            ]);

            $userRole = User::where('id',$request->userId)->first()->assignRole('Usuario');
        }

        return $this->ok([
            'status' => 'Success',
            'data' => [
                'user' => $userRole
            ]
        ]);
    }

    public function find(Request $request){
        $nombre = $request->nombre;
        $a_paterno = $request->a_paterno;
        $a_materno = $request->a_materno;

        $usersFind = User::where('nombre', 'like', '%' . $nombre . '%')->where('apellido_paterno', 'like', '%' . $a_paterno . '%')->where('apellido_materno', 'like', '%' . $a_materno . '%')->take(5)->get();

        return $this->ok([
            'status' => 'Success',
            'data' => [
                'users' => $usersFind,
            ]
        ]);
    }

    public function updateFirebase(Request $request){

        $fire = FirebaseToken::where('token', $request->firebase_token)->first();
        $firebase = "";

        if(!$fire){
            $firebase = FirebaseToken::create([
                'user_id' => $request->userId,
                'token' => $request->firebase_token
            ]);
        }

        return $this->ok([
            'status' => 'Success',
            'data' => []
        ]);
    }
}
