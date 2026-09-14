<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiFormRequest;

//validacion para CREAR producto (POST /api/products).
//OJO: sale_price NO se valida ni se recibe, lo calcula el modelo (neto + 19%)
class StoreProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            //sku unico, es el codigo con el q softland identifica el producto
            'sku'  => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],

            //corta es varchar(255), larga es text (sin tope)
            'short_description' => ['required', 'string', 'max:255'],
            'long_description'  => ['required', 'string'],

            //la imagen depende de por donde entra: desde la web viene un archivo (hasFile),
            //desde la api viene un string con la ruta/url. una sola imagen por producto
            'image' => $this->hasFile('image')
                ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
                : ['required', 'string', 'max:255'],

            //unsignedInteger → entero, sin negativos, tope de un INT sin signo.
            //sin el max la bd lo trunca en silencio. min 1 pq un producto a $0 no tiene sentido
            'net_price' => ['required', 'integer', 'min:1', 'max:4294967295'],

            //los stocks si pueden ser 0 (producto agotado)
            'current_stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'minimum_stock' => ['required', 'integer', 'min:0', 'max:4294967295'],

            //umbrales coherentes: minimo <= bajo <= alto. gte:campo compara contra
            //el otro campo del mismo request
            'low_stock'  => ['required', 'integer', 'min:0', 'max:4294967295', 'gte:minimum_stock'],
            'high_stock' => ['required', 'integer', 'min:0', 'max:4294967295', 'gte:low_stock'],
        ];
    }
}
