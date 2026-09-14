<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

//validacion para ACTUALIZAR usuario (PUT/PATCH /api/users/{id}).
//mismas reglas q el store pero con 'sometimes': si el campo no viene no se valida
//(sirve pa PATCH parcial), pero si viene NO puede venir vacio (required)
class UpdateUserRequest extends ApiFormRequest
{
    public function rules(): array
    {
        //el id viene de la ruta {id}. lo necesito pa q unique ignore al mismo usuario,
        //si no, actualizar sin cambiar el email tiraria "el email ya esta en uso"
        $userId = $this->route('id');

        return [
            'rut' => ['sometimes', 'required', 'string', 'max:12', 'regex:/^(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]$/', Rule::unique('users', 'rut')->ignore($userId)],

            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name'  => ['sometimes', 'required', 'string', 'max:100'],

            'email' => ['sometimes', 'required', 'email', 'max:150', 'ends_with:@ventasfix.cl', Rule::unique('users', 'email')->ignore($userId)],

            //si mandan password se cambia (el modelo la vuelve a hashear), si no, queda la misma
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ];
    }
}
