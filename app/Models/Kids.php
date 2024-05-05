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
        'updated_at'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id')->with('sexo');
    }

    public function tutors(){
        return $this->hasMany(KidsHasTutor::class, 'kid_id', 'id')->with('user');
    }
    
}
