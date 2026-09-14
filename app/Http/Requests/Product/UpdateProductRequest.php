<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

//validacion para ACTUALIZAR producto (PUT/PATCH /api/products/{id}).
//'sometimes' = si no viene no se valida, si viene no puede ir vacio
class UpdateProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            //unique ignorando el propio producto, si no, no podrias actualizar sin cambiar el sku
            'sku'  => ['sometimes', 'required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'name' => ['sometimes', 'required', 'string', 'max:150'],

            'short_description' => ['sometimes', 'required', 'string', 'max:255'],
            'long_description'  => ['sometimes', 'required', 'string'],

            'image' => ['sometimes', 'required', 'string', 'max:255'],

            //si cambia el neto, el modelo recalcula solo el sale_price al guardar
            'net_price' => ['sometimes', 'required', 'integer', 'min:1', 'max:4294967295'],

            'current_stock' => ['sometimes', 'required', 'integer', 'min:0', 'max:4294967295'],

            //los 3 umbrales van juntos: si tocas uno tienes q mandar los 3 (required_with),
            //asi la regla minimo <= bajo <= alto siempre se puede comprobar
            'minimum_stock' => ['required_with:low_stock,high_stock', 'integer', 'min:0', 'max:4294967295'],
            'low_stock'     => ['required_with:minimum_stock,high_stock', 'integer', 'min:0', 'max:4294967295', 'gte:minimum_stock'],
            'high_stock'    => ['required_with:minimum_stock,low_stock', 'integer', 'min:0', 'max:4294967295', 'gte:low_stock'],
        ];
    }
}
