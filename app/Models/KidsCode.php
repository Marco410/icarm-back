<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KidsCode extends BaseModel
{
    use HasFactory;


    protected $table = 'kids_code';
    protected $fillable = [
        'kid_id','user_id','code','is_valid'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id')->with('sexo');
    }

    public function kid(){
        return $this->hasOne(Kids::class, 'id', 'kid_id');
    }
    
}
