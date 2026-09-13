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

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->errorResponse('Los datos enviados no son válidos.', 422, $validator->errors()->toArray())
        );
    }
}
