{{-- organismo: ficha de un producto (2.2 obtener por id): imagen grande + datos + stocks --}}
@props(['product'])
<div class="grid gap-6 lg:grid-cols-3">
    <x-atoms.ui.card class="overflow-hidden lg:col-span-1">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover">
    </x-atoms.ui.card>

    <x-atoms.ui.card class="space-y-6 p-6 lg:col-span-2">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-line pb-5">
            <div>
                <p class="font-mono text-xs text-muted">{{ $product->sku }}</p>
                <x-atoms.ui.heading :level="2">{{ $product->name }}</x-atoms.ui.heading>
                <x-atoms.ui.text class="mt-1">{{ $product->short_description }}</x-atoms.ui.text>
            </div>
            <x-molecules.table.stock-badge :product="$product" />
        </div>

        <dl class="grid gap-5 sm:grid-cols-2">
            <x-molecules.ui.detail-item label="Precio neto">${{ number_format($product->net_price, 0, ',', '.') }}</x-molecules.ui.detail-item>
            <x-molecules.ui.detail-item label="Precio de venta (IVA 19% incluido)"><span class="text-lg font-semibold">${{ number_format($product->sale_price, 0, ',', '.') }}</span></x-molecules.ui.detail-item>
            <x-molecules.ui.detail-item label="Stock actual">{{ $product->current_stock }}</x-molecules.ui.detail-item>
            <x-molecules.ui.detail-item label="Umbrales (mín · bajo · alto)">{{ $product->minimum_stock }} · {{ $product->low_stock }} · {{ $product->high_stock }}</x-molecules.ui.detail-item>
            <x-molecules.ui.detail-item label="Creado">{{ $product->created_at->format('d/m/Y H:i') }}</x-molecules.ui.detail-item>
            <x-molecules.ui.detail-item label="Actualizado">{{ $product->updated_at->format('d/m/Y H:i') }}</x-molecules.ui.detail-item>
        </dl>

        <div class="space-y-1 border-t border-line pt-5">
            <x-atoms.ui.heading :level="3">Descripción</x-atoms.ui.heading>
            <p class="whitespace-pre-line text-sm">{{ $product->long_description }}</p>
        </div>
    </x-atoms.ui.card>
</div>
