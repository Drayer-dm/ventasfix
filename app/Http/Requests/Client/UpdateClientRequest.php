<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

//validacion para ACTUALIZAR cliente (PUT/PATCH /api/clients/{id}).
//'sometimes' = si no viene no se valida, si viene no puede ir vacio
class UpdateClientRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $clientId = $this->route('id');

        return [
            'company_rut' => ['sometimes', 'required', 'string', 'max:12', 'regex:/^(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]$/', Rule::unique('clients', 'company_rut')->ignore($clientId)],

            'business_sector' => ['sometimes', 'required', 'string', 'max:100'],
            'business_name'   => ['sometimes', 'required', 'string', 'max:150'],

            'phone'   => ['sometimes', 'required', 'string', 'max:20', 'regex:/^\+?[\d\s]{8,20}$/'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],

            'contact_name'  => ['sometimes', 'required', 'string', 'max:150'],
            'contact_email' => ['sometimes', 'required', 'email', 'max:150'],
        ];
    }
}
