<?php

namespace App\Models;

class NotificationModel extends BaseModel
{
    protected $table = "notification";
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'sender_id', 'title','body','data','type','seen','fe_alta','fe_update'
    ];

}

