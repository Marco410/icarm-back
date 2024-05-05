<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KidsHasTutor extends Model
{
    use HasFactory;


    protected $table = 'kids_has_tutor';
    protected $fillable = [
        'kid_id','tutor_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'tutor_id')->with('sexo');
    }

    public function kid(){
        return $this->hasOne(Kids::class, 'id', 'kid_id');
    }
    
}
