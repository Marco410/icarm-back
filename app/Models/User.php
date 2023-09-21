<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    protected $table = 'users';
    protected $guard_name = 'api';


    protected $fillable = [
        'nombre', 'apellido_p', 'apellido_m','telefono','email','fecha_nacimiento', 
    ];

    protected $hidden =[
        'password'
    ];

    public function iglesia(){
        return $this->hasOne(Iglesia::class);
    }

    public function evento(){
        return $this->hasMany(Evento::class);
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
