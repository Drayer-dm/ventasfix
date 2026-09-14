<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;

//validacion para CREAR usuario (POST /api/users). hereda de ApiFormRequest asi q
//si algo falla el 422 sale solo con nuestro envelope, el controller ni se entera
class StoreUserRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            //rut unico en la tabla. acepto con puntos (12.345.678-9) o sin (12345678-9),
            //y la k del digito verificador en mayus o minus
            'rut' => ['required', 'string', 'max:12', 'regex:/^(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]$/', 'unique:users,rut'],

            //nombre y apellido: varchar(100) en la migracion
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],

            //el email es el username, por eso unique. ends_with es la regla del enunciado:
            //todos los trabajadores son @ventasfix.cl, si no termina asi no entra
            'email' => ['required', 'email', 'max:150', 'ends_with:@ventasfix.cl', 'unique:users,email'],

            //min 8 pa q no metan "123". el hash lo hace el modelo con el cast hashed,
            //aca solo llega en texto plano y se valida
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
