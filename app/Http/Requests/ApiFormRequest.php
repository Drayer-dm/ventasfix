<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


  //Base de todos los FormRequest de la API.
  //Cuando la validación falla, responde 422 con el envelope de ApiResponse
  //en vez del formato por defecto de Laravel.

abstract class ApiFormRequest extends FormRequest
{
    use ApiResponse;


      //Siempre true: la autorización la resuelve el middleware JWT.

    public function authorize(): bool
    {
        return true;
    }

     // Laravel llama a este método cuando rules() no se cumple.
     // los mismos requests los usa la web (Blade) y la API, entonces:
     //  - si la peticion es /api/* → 422 en json con nuestro envelope
     //  - si es web → lo normal de laravel: redirect atras con $errors y old() (parent)

    protected function failedValidation(Validator $validator): void
    {
        if ($this->is('api/*') || $this->expectsJson()) {
            throw new HttpResponseException(
                $this->errorResponse('Los datos enviados no son válidos.', 422, $validator->errors()->toArray())
            );
        }

        parent::failedValidation($validator);
    }
}
