<?php

namespace App\Models\Tenant;

use App\Models\ApiModel;

class ClientApiInfo extends ApiModel
{
    protected $table = 'client_api_info';
    protected $primaryKey = 'client_id';
    public $timestamps = false;

    protected $fillable = [
        'access_token',        
        'api_version',
        'api_active',
        'api_granted_date'
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
