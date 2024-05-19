<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserHasMinisterios;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    protected $table = 'users';
    protected $guard_name = 'api';


    protected $fillable = [
        'nombre', 'apellido_paterno', 'apellido_materno','telefono','email','fecha_nacimiento', 'sexo','sexo_id','iglesia_id','password','pais_id','firebase_token','active','pass_update','foto_perfil','asignacion'
    ];

    protected $hidden =[
        'password'
    ];

    public function iglesia(){
        return $this->hasOne(Iglesia::class,'id','iglesia_id');
    }

    public function evento(){
        return $this->hasMany(Evento::class);
    }

    public function pais(){
        return $this->hasOne(Pais::class, 'id', 'pais_id');
    }

    public function sexo(){
        return $this->hasOne(Sexo::class,'id','sexo_id');
    }

    public function maestro_vision(){
        return $this->hasOne(Maestro::class)->with('maestro_user');
    }

    public function classroom(){
        return $this->hasMany(Classroom::class)->where('is_in',1)->with('kid');
    }

    public function firebaseToken(){
        return $this->hasMany(FirebaseToken::class);
    }

    public function ministerios(){
        return $this->hasMany(UserHasMinisterios::class)->with('ministerio');
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
