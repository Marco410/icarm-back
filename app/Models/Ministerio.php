<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ministerio extends BaseModel
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
