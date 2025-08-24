<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Settings extends BaseModel
{
    use HasFactory;


    protected $table = 'settings';
    protected $fillable = [
        'name','value','important','data'
    ];

}
