<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

//capa de servicio de productos: controller → service → model.
//el sale_price nunca se manda desde aca, lo calcula el modelo en el evento saving
class ProductService
{
    public function getAll(): Collection
    {
        return Product::orderBy('id')->get();
    }

    //null si no existe → el controller responde 404
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    //$data validada por StoreProductRequest. al hacer create() se dispara saving
    //y el modelo setea sale_price = net_price * 1.19
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    //si en $data viene net_price, saving vuelve a correr y recalcula el sale_price
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
