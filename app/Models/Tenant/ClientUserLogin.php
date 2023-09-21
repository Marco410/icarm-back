<?php

namespace App\Models\Tenant;

use App\Models\ApiModel;

class ClientUserLogin extends ApiModel
{
    protected $table = 'client_user_login';
    //protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'client_id',        
        'username',
        'email',
        'name',
        'lastname',
        'second_surname',
        'gender',
        'avatar',
        'created_at',
        'token'
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
    ];

    public static $rulesInsert = [
        // 'user_id' => [], 'name' => [], 'logo' => [], 'active' => []
    ];

    public static $rulesUpdate = [
        // 'user_id' => [], 'name' => [], 'logo' => [], 'active' => []
    ];

}
