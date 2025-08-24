<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends BaseModel
{
    use HasFactory;

    protected $table = 'evento';


    protected $fillable = [
        'id','iglesia_id','nombre','link','fecha_inicio','fecha_fin','descripcion','direccion','img_vertical','img_horizontal','is_favorite','can_register','is_public','reminder','confetti'
    ];

    protected $hidden = [];


    public function user(){
        return $this->belongTo(User::class)->with('sexo');
    }

    public function iglesia(){
        return $this->hasOne(Iglesia::class, 'id', 'iglesia_id');
    }

    public function user_interested(){
        return $this->hasOne(Interested::class, 'id','user_id');
    }

    public function interested(){
        return $this->hasMany(Interested::class);
    }
}
