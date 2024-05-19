<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministerio extends Model
{
    use HasFactory;


    protected $table = 'ministerios';
    protected $fillable = [
        'id',
        'name'
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];

}
