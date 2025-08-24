<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends BaseModel
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
