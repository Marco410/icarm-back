<?php

namespace App\Http\Controllers;

use App\Classes\PlainObjects\AvatarPO;
use App\Classes\PlainObjects\ExternalUserPO;
use App\Models\ViewUser;
use App\Models\AccessLog;
use App\Models\UserSession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\TenantService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Classes\PlainObjects\UserPO;
use App\Models\Tenant\ClientApiInfo;
use App\Classes\PlainObjects\PersonPO;
use App\Classes\PlainObjects\TenantPO;

class TenantAuthController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('verify.authorization.jwt', ['except' => ['login', 'test']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenant_login(Request $request)
    {
        $access_token       = $request->access_token;
        $tenant_pwd         = $request->password;
        $username           = $request->username;
        $birthdate          = $request->birthdate;
        $email              = $request->email;
        $name               = $request->name;
        $lastname           = $request->lastname;
        $second_surname     = $request->second_surname;
        $gender             = $request->gender;
        $avatarBase64       = $request->avatar;
        $token              = (string) Str::uuid();

        $tenant = new TenantPO();
        $tenant->setAccessToken($access_token);
        $tenant->setPassword($tenant_pwd);

        $user = new UserPO();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setTempAccessToken($token);
    
        $avatarImg = new AvatarPO();
        $avatarImg->setAvatarBase64($avatarBase64);

        $person = new PersonPO();
        $person->setName($name);
        $person->setLastname($lastname);
        $person->setSecondSurname($second_surname);
        $person->setBirthdate($birthdate);
        $person->setGender($gender);
        $person->setAvatar($avatarImg);

        $usuarioExterno = new ExternalUserPO();
        $usuarioExterno->setUser($user);
        $usuarioExterno->setPerson($person);
        $usuarioExterno->setTenant($tenant);

        $tenantService = new TenantService();

        $url = env('APP_FRONTEND_URL') . "auth/tenant_login/sessId:" . $token;

        if (!$tenantService->clientApiIsActive($tenant)) {
            return $this->notFound('Recurso no encontrado', 404.01);
        }
        
        $tenantService->saveExternalUserLogin($usuarioExterno);
       
        return $this->ok([
            'url_login' => base64_encode($url)
        ]);
    }


    public function tenant_manage_user(Request $request) 
    {
        $tenatService = new TenantService();
        $userM = $tenatService->createOrUpdateUserFromExternal($request->sessId);

        $user = new ViewUser();

        $user = $user->where('username', $userM->username)->first();

        if (empty($user)) {
            return $this->notFound('Recurso no encontrado', 404.01);
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

        if ($newLogin) {
            $userSession = UserSession::newLogin([
                'user_id' => $user->id,
                'token' => $token
            ]);
        }

        $accessLog = AccessLog::create(['user_id' => $user->id]);

        return $this->ok([
            'token' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => JWTAuth::factory()->getTTL() * 60,
            // '$newLogin' => $newLogin,
            // '$$accessLog' => $$accessLog
            // '$userSession' => $userSession
        ]);           
    }

    public function tenant_delete_user(Request $request) 
    {        
        $access_token       = $request->access_token;
        $tenant_pwd         = $request->password;
        $username           = $request->username;

        $tenant = new TenantPO();
        $tenant->setAccessToken($access_token);
        $tenant->setPassword($tenant_pwd);

        $user = new UserPO();
        $user->setUsername($username);

        $usuarioExterno = new ExternalUserPO();
        $usuarioExterno->setUser($user);
        $usuarioExterno->setTenant($tenant);                       

        $tenantService = new TenantService();

        if (!$tenantService->clientApiIsActive($tenant)) {
            return $this->notFound('Recurso no encontrado', 404.01);
        }
        
        $result = $tenantService->deleteUserFromTenant($usuarioExterno);
        
        return $this->ok([
            'data' => true
        ]);           
    }    

}