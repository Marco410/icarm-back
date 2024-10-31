<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;


    protected $table = 'user_has_teacher';
    protected $fillable = [
        'id',
        'user_id',
        'maestro_id',
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];


    public function maestro_user(){
        return $this->hasOne(User::class,'id','maestro_id')->with('sexo');
    }

}
