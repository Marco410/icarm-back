<?php

namespace App\Http\Requests;

use App\Models\UserSession;
use App\Traits\Http\Requests\FiltersRequestInput;
use App\Traits\Http\Requests\Messages;
use Illuminate\Foundation\Http\FormRequest;

class UserSessionInsertRequest extends FormRequest
{
    use FiltersRequestInput;
    use Messages {
        messages as apiRequestMessages;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return UserSession::$rulesInsert;
    }

    public function messages()
    {
        return array_merge($this->apiRequestMessages(), [
            //'email.required' => 'Correo electrónico requerido'
        ]);
    }

    public function filters()
    {
        return [
            // 'name' => 'strtolower',
            // 'email' => 'trim|lowercase',
        ];
    }
}
