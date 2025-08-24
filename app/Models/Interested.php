<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interested extends BaseModel
{
    use HasFactory;

    protected $table = 'interested';


    protected $fillable = [
        'id','user_id','evento_id'
    ];

    protected $hidden = [];

   public function user(){
        return $this->belongsTo(User::class)->with('sexo');
    }

    public function evento(){
        return $this->belongsTo(Evento::class);
    }

}
