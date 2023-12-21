<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
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
        return $this->belongsToMany(User::class);
    }

    public function kid(){
        return $this->hasOne(Kids::class, 'id', 'kid_id')->with('user');
    }
    
}
