<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Encontrado extends BaseModel
{
    use HasFactory;

    protected $table = 'encontrado';


    protected $fillable = [
        'id','user_id','user_invited_id','evento_id','nombre','a_paterno','a_materno','email','edad','genero','estado_civil','telefono','ref_nombre','ref_telefono',
    ];

    protected $hidden = [];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id')->with('sexo');
    }

    public function userInvited(){
        return $this->hasOne(User::class, 'id', 'user_invited_id')->with('sexo');
    }

    public function evento(){
        return $this->hasOne(Evento::class, 'id', 'evento_id');
    }

}
