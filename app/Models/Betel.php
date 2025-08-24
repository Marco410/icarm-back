<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Betel extends BaseModel
{
    use HasFactory;


    protected $table = 'betel';
    protected $fillable = [
        'id',
        'user_id',
        'user2_id',
        'user_anf_id',
        'user_anf2_id',
        'user_name',
        'user2_name',
        'user_anf_name',
        'user_anf2_name',
        'img',
        'map_url',
        'direccion',
        'telefono',
        'lat',
        'lng',
        'active'
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];


    public function user(){
        return $this->hasOne(User::class,'id','user_id')->with('sexo');
    }

    public function user2(){
        return $this->hasOne(User::class,'id','user2_id')->with('sexo');
    }

    public function user_anf(){
        return $this->hasOne(User::class,'id','user_anf_id')->with('sexo');
    }

    public function user_anf2(){
        return $this->hasOne(User::class,'id','user_anf2_id')->with('sexo');
    }

}
