<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    use HasFactory;


    protected $table = 'firebase_token';
    protected $fillable = [
        'user_id','token'
    ];

    protected $hidden = [
        'id','updated_at','created_at'
    ];

    public function user(){
        return $this->belongsTo(User::class)->with('sexo');
    }

}
