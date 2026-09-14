{{-- molecula: precio de venta grande y el neto chico abajo --}}
@props(['product'])
<div class="leading-tight">
    <p class="font-medium">${{ number_format($product->sale_price, 0, ',', '.') }}</p>
    <p class="text-xs text-muted">neto ${{ number_format($product->net_price, 0, ',', '.') }}</p>
</div>
