<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\CourseUser;
use App\Models\Label;
use App\Models\Person;
use App\Models\UserContext;
use App\Models\User;
use App\Models\ViewUserRelationshipWithCourse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class DefaultController extends ApiController
{
    public function labelsByKeyLanguage($tag = 'es-MX')
    {
        return $this->ok(Label::getLabelsByKeyLanguage($tag));
    }

    public function initialUserData()
    {
        return $this->ok(User::getInitialData($this->getUserLoggedIn()));
    }

    public function profile()
    {
        return $this->ok(User::getAllData($this->getUserLoggedIn()->id));
    }

    public function saveProfile(Request $request)
    {
        $data = User::saveAllData($request->toArray());

        if ($data != null) {
            return $this->ok($data);
        }

        return $this->conflict();
    }

    public function avatar(Request $request)
    {
        $rules = ['file' => 'image'];

        $this->validate($request, $rules);

        $path = 'avatar';

        $file = Storage::disk('public')->put($path, $request->file);

        $user = $this->getUserLoggedIn();

        $person = Person::find($user->person_id);

        if ($person->avatar != null && $person->avatar != '') {
            Storage::disk('public')->delete($person->avatar);
        }

        $file = str_replace($path . '/', '', $file);

        $person->avatar = $file;

        $person->update();

        return $this->ok([
            'file' => [
                'name' => $file,
                'fullPath' => storage_path('app/public/' . $path . '/' . $file)
            ]
        ]);
    }

    public function share(Request $request)
    {

        $rules = ['users.*.email' => 'required|email'];

        $this->validate($request, $rules);

        $userLoggedIn = $this->getUserLoggedIn();

        $host = [
            'name' => $userLoggedIn->full_name,
            'logo' => $userLoggedIn->avatar
        ];

        $urlToRegister = env('APP_FRONTEND_URL') . '/auth/register/';

        $userContext = UserContext::with('client')->firstWhere('user_id', $userLoggedIn->id);

        if ($userContext == null) {
            return $this->badRequest('No hay cliente seleccionado');
        } else {
            $host = [
                'name' => $userContext->client->name,
                'logo' => $userContext->client->logo
            ];
        }

        $data = [];

        foreach ($request->users as $user) {
            // \Mail::to($user['email'], $user['name'])->queue(new \App\Mail\InvitationToRegister([
            \Mail::to($user['email'], $user['name'])->send(new \App\Mail\InvitationToRegister([
                'host' => $host,
                'urlToRegister' => $urlToRegister . Crypt::encryptString($user['email']),
            ]));

            $data[$user['email']] = [
                'user' => $user,
                'clientUser' => Client::registerInvitation($userContext->client->id, $user['email'], $user['name'])
            ];
        }
        
        if (!empty($request->courses) && !empty($data)) {

            $idsCourses = \Illuminate\Support\Arr::pluck(ViewUserRelationshipWithCourse::whereIn('course_id', $request->courses)->where('client_id', $userContext->client->id)->ofType('owner')->get(), 'course_id');

            if (!empty($idsCourses)) {
                foreach ($idsCourses as $course_id) {
                    foreach ($data as $dt) {
                        $data[$dt['user']['email']]['CourseUser'] =
                            CourseUser::firstOrCreate([
                                'course_id' => $course_id,
                                'user_id' => $dt['clientUser']->user_id
                            ]);
                    }
                }
            }
        }

        return $this->ok(array_values($data));
    }

    public function getUserByEncryptedEmail($encryptedEmail)
    {
        $user = User::where('email', Crypt::decryptString($encryptedEmail))->firstOrFail();

        $dataUser = User::getAllData($user->id);

        if ($dataUser == null) {
            return $this->notFound();
        }

        $registered = false;

        $cliensUser = ClientUser::where('user_id', $user->id)->get();

        foreach ($cliensUser as $dt) {
            if ($dt->registered) {
                $registered = true;
                ClientUser::where('user_id', $user->id)->update(['registered' => ClientUser::REGISTERED]);
                break;
            }
        }

        return $this->ok([
            'registered' => $registered,
            'data' => $dataUser
        ]);
    }

    public function register(Request $request)
    {
        if (!isset($request['user']['id'])) {
            return $this->conflict('Tiene que invitarte alguien');
        }
        $data = User::saveAllData($request->toArray());

        if ($data != null) {

            ClientUser::where('user_id', $request['user']['id'])->update(['registered' => ClientUser::REGISTERED]);

            return $this->ok($data);
        }

        return $this->conflict();
    }
}
