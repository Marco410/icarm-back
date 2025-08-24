<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaseLista extends BaseModel
{
    use HasFactory;

    protected $table = 'paselista';
    protected $fillable = [
        'id_persona','evento','fh','evento_id'
    ];

    
}
