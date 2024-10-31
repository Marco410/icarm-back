<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
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
