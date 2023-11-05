<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    use HasFactory;


    protected $table = 'kids';
    protected $fillable = [
        'nombre','user_id','a_paterno','a_materno','fecha_nacimiento','sexo','enfermedad','active'
    ];

    protected $hidden = [
        'id','updated_at','created_at'
    ];

    
}
