<?php

namespace App\Traits\Http\Requests;

trait Messages
{
    public function messages()
    {
        return [
            'name.required' => 'Nombre requerido',
            'email.email' => 'Formato de correo electrónico incorrecto',
            'email.required' => 'Correo electrónico requerido',
            'email.unique' => 'El correo electrónico ya fue tomado',
            'password.required' => 'La contraseña es requerida'
        ];
    }
}
