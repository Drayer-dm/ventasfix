<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

//capa de servicio de productos: controller → service → model.
//el sale_price nunca se manda desde aca, lo calcula el modelo en el evento saving.
//la imagen puede llegar como archivo (web) o como string ruta/url (api), este service resuelve las 2
class ProductService
{
    //carpeta dentro de public/ donde quedan las imagenes subidas
    private const IMAGE_DIR = 'images/products';

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
        $data['image'] = $this->resolveImage($data['image']);

        return Product::create($data);
    }

    //si en $data viene net_price, saving vuelve a correr y recalcula el sale_price.
    //si viene imagen nueva, se guarda y se borra la anterior del disco
    public function update(Product $product, array $data): Product
    {
        if (array_key_exists('image', $data)) {
            $data['image'] = $this->resolveImage($data['image'], $product->image);
        }

        $product->update($data);

        return $product->fresh();
    }

    //borra el registro y tambien el archivo, pa no dejar basura en public/
    public function delete(Product $product): void
    {
        $this->deleteImageFile($product->image);

        $product->delete();
    }

    //si es un archivo subido lo muevo a public/images/products con nombre unico y devuelvo
    //la ruta relativa (eso es lo q se guarda en la bd). si es string (api) lo dejo tal cual
    private function resolveImage(mixed $image, ?string $previous = null): string
    {
        if (! $image instanceof UploadedFile) {
            return $image;
        }

        $filename = Str::uuid() . '.' . strtolower($image->getClientOriginalExtension());
        $image->move(public_path(self::IMAGE_DIR), $filename);

        $this->deleteImageFile($previous);

        return self::IMAGE_DIR . '/' . $filename;
    }

    //solo borro archivos q esten en NUESTRA carpeta, una url externa no se toca
    private function deleteImageFile(?string $path): void
    {
        if ($path && str_starts_with($path, self::IMAGE_DIR . '/') && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
