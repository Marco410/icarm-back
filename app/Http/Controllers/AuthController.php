<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSession;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth:api', ['except' => ['login', 'test']]);
        $this->middleware('verify.authorization.jwt', ['except' => ['login', 'test','register']]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

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
            return $this->notFound('Usuario no encontrado', 404.01);
        }

        if ($user->password != hash('sha512', $request->password)) {
            return $this->forbidden('Contraseña incorrecta', 403.01);
        }

       /*  if (!$user->active) {
            return $this->forbidden('Usuario inactivo', 403.02);
        } */

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

        if ($newLogin) {
            $userSession = UserSession::newLogin([
                'user_id' => $user->id,
                'token' => $token
            ]);
        }

        $user->roles;

        return $this->ok([
            'token' => $token,
            'user' => $user,
            'tokenType' => 'bearer'/* ,
            'expiresIn' => JWTAuth::factory()->getTTL() * 60,
            // '$newLogin' => $newLogin,
            // '$$accessLog' => $$accessLog
            // '$userSession' => $userSession */
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

        $this->validate($request,[
            'email' => 'required|unique:users,email',
        ]);
        $nombre = $request->nombre;
        $apellidos = $request->apellidos;
        $email = $request->email;
        $password = hash('sha512', $request->password);

        $user = User::create([
            'nombre' => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $email,
            'password' => $password,
            'pais' => $request->pais,
            'estado' => $request->estado,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
            'embarazada' => $request->embarazada,
            'active' => 1
        ])->assignRole('Mama');

        $customClaims = ['custom' => [/*'user' => $user*/]];

        $token = JWTAuth::claims($customClaims)->fromUser($user);
        $newLogin = true;


        if ($newLogin) {
            $userSession = UserSession::newLogin([
                'user_id' => $user->id,
                'token' => $token
            ]);
        }
        
        if($request->email_confirm){
            //TODO: enviar email
        }

        return $this->ok([
            'token' => $token,
            'user' => $user,
            'tokenType' => 'bearer'/* ,
            'expiresIn' => JWTAuth::factory()->getTTL() * 60,
            // '$newLogin' => $newLogin,
            // '$$accessLog' => $$accessLog
            // '$userSession' => $userSession */
        ]);
    }
}
