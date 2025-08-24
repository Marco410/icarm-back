<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ads extends BaseModel
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
