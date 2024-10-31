<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = "notification";
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'sender_id', 'title','body','data','type','seen','fe_alta','fe_update'
    ];
}

