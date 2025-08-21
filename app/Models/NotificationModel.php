<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use DateTimeZone;

class NotificationModel extends Model
{
    protected $table = "notification";
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'sender_id', 'title','body','data','type','seen','fe_alta','fe_update'
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new DateTimeZone('America/Mexico_City'))
                    ->format('Y-m-d H:i:s');
    }
}

