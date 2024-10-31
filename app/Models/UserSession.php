<?php

namespace App\Models;

// use App\Scopes\UserSessionScope;
use App\Traits\Models\AccessorsMutators\UserSession as UserSessionAccessorsMutators;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class UserSession extends ApiModel
{
    use SoftDeletes, UserSessionAccessorsMutators;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id', 'last_login', 'number_logins', 'token', 'last_request'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $appends = [];

    public static $rulesInsert = [
        // 'user_id' => [], 'token' => [], 'last_request' => [], 'number_logins' => []
    ];

    public static $rulesUpdate = [
        // 'user_id' => [], 'token' => [], 'last_request' => [], 'number_logins' => []
    ];

    public static function newLogin($data)
    {
        $data['last_login'] = Carbon::now();
        $data['last_request'] = Carbon::now();

        $UserSession = UserSession::find($data['user_id']);

        if (empty($UserSession)) {
            // dd($data);
            return UserSession::create($data);
        }

        $UserSession->last_login = $data['last_login'];
        $UserSession->number_logins++;
        $UserSession->token = $data['token'];
        $UserSession->last_request = $data['last_request'];
        $UserSession->update();

        return $UserSession;
    }

    public static function clearData($user_id)
    {
        $UserSession = UserSession::find($user_id);
        if (!empty($UserSession)) {
            $UserSession->token = null;
            $UserSession->last_request = null;
            $UserSession->update();
        }else{
            $data['user_id'] = $user_id;
            $data['last_login'] = Carbon::now();
            $data['last_request'] = Carbon::now();
            return UserSession::create($data);
        }
        return $UserSession;
    }


    public static function updateLastRequest($user_id)
    {
        $UserSession = UserSession::find($user_id);

        if (!empty($UserSession)) {
            $UserSession->last_request = Carbon::now();
            $UserSession->update();
        }else{
            $data['user_id'] = $user_id;
            $data['last_login'] = Carbon::now();
            $data['last_request'] = Carbon::now();
            return UserSession::create($data);
        }
        return $UserSession;
    }

    public static function refreshToken($user_id, $token)
    {
        $UserSession = UserSession::find($user_id);
        if (!empty($UserSession)) {
            $UserSession->token = $token;
            $UserSession->last_request = Carbon::now();
            $UserSession->update();
        }else{
            $data['user_id'] = $user_id;
            $data['last_login'] = Carbon::now();
            $data['last_request'] = Carbon::now();
            return UserSession::create($data);
        }
        return $UserSession;
    }
}
