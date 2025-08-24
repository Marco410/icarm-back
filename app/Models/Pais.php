<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pais extends BaseModel
{
    use HasFactory;


    protected $table = 'paises';
    protected $fillable = [
        'id',
        'nombre'
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];

}
