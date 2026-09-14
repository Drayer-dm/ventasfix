<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\ApiFormRequest;

//validacion para CREAR cliente empresa (POST /api/clients)
class StoreClientRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            //rut de la empresa, mismo formato q el de usuarios, unico
            'company_rut' => ['required', 'string', 'max:12', 'regex:/^(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]$/', 'unique:clients,company_rut'],

            //rubro y razon social
            'business_sector' => ['required', 'string', 'max:100'],
            'business_name'   => ['required', 'string', 'max:150'],

            //telefono: numeros, espacios y un + opcional al inicio (+56 9 1234 5678)
            'phone'   => ['required', 'string', 'max:20', 'regex:/^\+?[\d\s]{8,20}$/'],
            'address' => ['required', 'string', 'max:255'],

            //persona de contacto. el email aca NO tiene q ser @ventasfix.cl, es de la otra empresa
            'contact_name'  => ['required', 'string', 'max:150'],
            'contact_email' => ['required', 'email', 'max:150'],
        ];
    }
}
