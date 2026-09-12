<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku',50)->unique();
            $table->string('name',150);
            $table->string('short_description',255);
            $table->text('long_description');
            $table->string('image');
            $table->unsignedInteger('net_price');//Precio sin iva w
            $table->unsignedInteger('sale_price');//precio con iva w = net_price * 19%
            $table->unsignedInteger('current_stock');//Stock actual
            $table->unsignedInteger('minimum_stock');//Stock mínimo
            $table->unsignedInteger('low_stock');//Stock bajo
            $table->unsignedInteger('high_stock');//Stock máximo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
