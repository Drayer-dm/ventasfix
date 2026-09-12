<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    "sku",
    "name",
    "short_description",
    "long_description",
    "image",
    "net_price",
    "current_stock",
    "minimum_stock",
    "low_stock",
    "high_stock",
])]
class Product extends Model
{
    public const TAX_RATE = 0.19;

    protected function casts(): array
    {
        return [
            'net_price'=> 'integer',
            'sale_price' => 'integer',
            'current_stock' => 'integer',
            'minimum_stock' => 'integer',
            'low_stock' => 'integer',
            'high_stock' => 'integer',
        ];
    }
    //El eloquent llama el boosted() una sola vez la primera es para el modelo, de ahi pos se registran con el
    // listener @saving que dispara antes de cada insert asi el precio d venta queda conciente con el neto

    protected static function booted():void
    {
        static::saving(function (Product $product):void {
            $product->sale_price = self::calculateSalePrice($product->net_price);
        });
    }

    public static function calculateSalePrice(int $netPrice): int
    {
        return (int) round($netPrice * (1 + self::TAX_RATE));
    }
}
