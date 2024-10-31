<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;


    protected $table = 'pagos';
    protected $fillable = [
        'id',
        'id_persona',
        'evento_id',
        'concepto',
        'cantidad',
        'fecha_agrego',
    ];

    protected $hidden = [
       'updated_at'
    ];

}
