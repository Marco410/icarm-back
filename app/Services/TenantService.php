<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use App\Models\Course;
use App\Models\Person;
use App\Library\Constants;
use App\Models\CourseUser;
use App\Models\UserContext;
use App\Services\BaseService;
use App\Services\AvatarService;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\ClientApiInfo;
use App\Models\Tenant\ClientUserLogin;

class TenantService extends BaseService
{

    public function saveExternalUserData($data) 
    {

    }

    public function clientApiIsActive($tenant)
    {
        $result = true;

        $clientApiInfo = ClientApiInfo::where('access_token', $tenant->getAccessToken())->where('password', hash('sha512', $tenant->getPassword()))->first();

        if (empty($clientApiInfo) || !$clientApiInfo->api_active)
            $result = false;
            
        return $result;
    }

    public function saveExternalUserLogin($usuarioExterno)
    {
        $usu        = $usuarioExterno->getUser();
        $persona    = $usuarioExterno->getPerson();
        $tenant     = $usuarioExterno->getTenant();

        $client = ClientApiInfo::where('access_token', $tenant->getAccessToken())->first();

        $clientUserLogin = new ClientUserLogin();
        $clientUserLogin->client_id = $client->client_id;
        $clientUserLogin->username  = $usu->getUsername();
        $clientUserLogin->email  = $usu->getEmail();
        $clientUserLogin->name  = $persona->getName();
        $clientUserLogin->lastname  = $persona->getLastName();
        $clientUserLogin->second_surname  = $persona->getSecondSurname();
        $clientUserLogin->gender  = $persona->getGender();
        $clientUserLogin->avatar  = $persona->getAvatar()->getAvatarBase64();
        $clientUserLogin->token  = $usu->getTempAccessToken();


        $this->deleteOldLoginData($usuarioExterno);
        $clientUserLogin->save();
    }

    public function deleteOldLoginData($usuarioExterno) 
    {
        $usu        = $usuarioExterno->getUser();
        $tenant     = $usuarioExterno->getTenant();

        $client = ClientApiInfo::where('access_token', $tenant->getAccessToken())->first();
        ClientUserLogin::where('client_id', $client->client_id)->where('username', $usu->getUsername())->delete();
    }

    public function createOrUpdateUserFromExternal($sessId) 
    {

        $userLogin = ClientUserLogin::where('token', $sessId)->first();        

        /* Datos por salvar
            1. Usuario
            2. Persona
            3. Asignacion de cursos del cliente que lo da de alta
            4. Creacion de registro de contexto inicial
        */
        
        try {
                DB::beginTransaction();
            
                // Si existe el usuario, no se actualizan datos de perfil ni avatar
                $user = User::where('username', $userLogin->username)->first();                
                
                if($user) 
                {
                    // Si existe y esta inactivo, lo activamos
                    $userId = $user->id;
                    if($user->active == Constants::INACTIVE) {
                        $user->active = Constants::ACTIVE;
                        $user->save();
                    }
                }
                else 
                {
                    $avatarService = new AvatarService();
                    $avatarFilename = $avatarService->saveAvatarFileFromBase64($userLogin->avatar);
                    
                    $person = new Person();
                    $person->name = $userLogin->name;
                    $person->lastname = $userLogin->lastname;
                    $person->second_surname = $userLogin->second_surname;
                    $person->birthdate = $userLogin->birthdate;
                    $person->gender = $userLogin->gender;
                    $person->avatar = $avatarFilename;
                    $person->active = Constants::ACTIVE;

                    $person->save();
                    $personId = $person->id;
                
                    $user = new User();
                    $user->person_id = $personId;
                    $user->profile_id = Constants::ID_STUDENT_PROFILE;
                    $user->username = $userLogin->username;
                    $user->email = $userLogin->email;
                    $user->active = Constants::ACTIVE;

                    $user->save();
                    $userId = $user->id;
                }

                $coursesQuery = Course::where('client_id', $userLogin->client_id)->where('active', Constants::ACTIVE);
                
                $courses = $coursesQuery->get();

                $coursesIds = $coursesQuery->pluck('id')->toArray();

                $this->unlinkUserTenantCourses($userId, $coursesIds);            

                foreach($courses as $course)
                {
                    $userCourse = new CourseUser();
                    $userCourse->user_id = $userId;
                    $userCourse->course_id = $course->id;

                    $userCourse->save();
                }

                $userContext = UserContext::find($userId);

                if($userContext != null) 
                {
                    $userContext->client_id = $userLogin->client_id; 
                    $userContext->save();      
                }
                else {
                    $userContext = new UserContext();
                    $userContext->user_id = $userId;
                    $userContext->client_id = $userLogin->client_id;
                    $userContext->save();
                }          
        
                DB::commit();
            
            } catch (Throwable $e) {
                //dd($e);
                DB::rollback();
            }  
            
        return $user;
    }    

    public function deleteUserFromTenant($usuarioExterno)
    {
        $tenant     = $usuarioExterno->getTenant();
        $usu        = $usuarioExterno->getUser();

        $client = ClientApiInfo::where('access_token', $tenant->getAccessToken())->first();
        $clientId = $client->client_id;

        $usu = User::where('username', $usu->getUsername())->first();
        $userId = $usu->id; 

        $coursesQuery = Course::where('client_id', $clientId);                
        $coursesIds = $coursesQuery->pluck('id')->toArray();

        $this->unlinkUserTenantCourses($userId, $coursesIds);      
        
        // Esto es un error de diseño original
        // Se tiene que manejar el contexto si no, la aplicacion falla cuando no tiene uno
        $courseUser = CourseUser::where('user_id', $userId)->with('course')->first();
        
        if($courseUser != null)
        {
            $userContext = UserContext::find($userId);
            $userContext->client_id = $courseUser->course->client_id;
            $userContext->save();   
        }

        return $this->deleteOldLoginData($usuarioExterno) > 0;
    }

    public function unlinkUserTenantCourses($userId, $coursesIds)
    {
        // Esta tabla usa SoftDeletes
        CourseUser::where('user_id', $userId)->whereIn('course_id', $coursesIds)->withTrashed()->forceDelete();   
    }

}