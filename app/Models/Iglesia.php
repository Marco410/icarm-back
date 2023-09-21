<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iglesia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre','web','calle','numero','colonia','cp','ciudad','pais','lat','lng','telefono','facebook','instagram','youtube','pastores','horarios','mision','historia'
    ];
    //kjanscjnc

    public function user(){
        return $this->belongsTo(User::class);
    }
}
