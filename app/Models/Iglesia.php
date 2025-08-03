<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iglesia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre','web','calle','numero','colonia','cp','ciudad','estado','pais','lat','lng','telefono','facebook','instagram','youtube','pastores','horarios','mision','historia'
    ];

    public function user(){
        return $this->belongsTo(User::class)->with('sexo');
    }

    public function churchService(){
        return $this->hasMany(ChurchService::class, 'church_id', 'id');
    }
}
