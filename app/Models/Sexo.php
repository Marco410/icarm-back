<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sexo extends BaseModel
{
    use HasFactory;


    protected $table = 'sexo';
    protected $fillable = [
        'id',
        'nombre'
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];

}
