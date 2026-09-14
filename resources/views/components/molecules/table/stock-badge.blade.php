{{-- molecula: badge del stock segun los umbrales del producto.
     <= minimo → "Crítico", <= bajo → "Bajo", >= alto → "Alto", si no → "Normal" --}}
@props(['product'])
@php
    [$tone, $label] = match (true) {
        $product->current_stock <= $product->minimum_stock => ['high', 'Crítico'],
        $product->current_stock <= $product->low_stock     => ['mid', 'Bajo'],
        $product->current_stock >= $product->high_stock    => ['low', 'Alto'],
        default                                            => ['low', 'Normal'],
    };
@endphp
<x-atoms.ui.badge :tone="$tone">{{ $label }} · {{ $product->current_stock }}</x-atoms.ui.badge>
