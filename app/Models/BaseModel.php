<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DateTimeInterface;
use DateTimeZone;

class BaseModel extends Eloquent
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new DateTimeZone('America/Mexico_City'))
                    ->format('Y-m-d H:i:s');
    }
}