<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends BaseModel
{
    use HasFactory;


    protected $table = 'classroom';
    
    protected $fillable = [
        'user_id','kid_id','is_in'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->belongsToMany(User::class)->with('sexo');
    }

    public function kid(){
        return $this->hasOne(Kids::class, 'id', 'kid_id')->with(['user','tutors']);
    }
    
}
