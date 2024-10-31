<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;


    protected $table = 'ads';
    
    protected $fillable = [
        'title','subtitle','img','module','active'
    ];

    protected $hidden = [
        'updated_at'
    ];
    
}
