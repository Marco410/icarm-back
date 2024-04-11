<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaseLista extends Model
{
    use HasFactory;

    protected $table = 'paselista';
    protected $fillable = [
        'id_persona','evento','fh','evento_id'
    ];

    
}
