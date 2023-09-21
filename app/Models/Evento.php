<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'iglesia_id','nombre','fecha_inicio','fecha_fin','descripcion','imagen'
    ];

    protected $hidden = [
        'id','updated_at','created_at'
    ];


    public function user(){
        return $this->belongTo(User::class);
    }
}
