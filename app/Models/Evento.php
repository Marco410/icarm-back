<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';


    protected $fillable = [
        'id','iglesia_id','nombre','fecha_inicio','fecha_fin','descripcion','imagen'
    ];

    protected $hidden = [];


    public function user(){
        return $this->belongTo(User::class);
    }
}
