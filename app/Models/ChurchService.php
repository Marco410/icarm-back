<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChurchService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','day','time', 'church_id'
    ];


    public function church(){
        return $this->hasOne(Iglesia::class, 'id', 'church_id');
    }

}
