{{-- organismo: tabla de productos (2.1 listar). muestra miniatura, precio venta/neto y estado del stock --}}
@props(['products'])
<x-atoms.ui.card>
    <x-atoms.table.table>
        <thead>
            <tr>
                <x-atoms.table.th>Producto</x-atoms.table.th>
                <x-atoms.table.th>SKU</x-atoms.table.th>
                <x-atoms.table.th>Precio</x-atoms.table.th>
                <x-atoms.table.th>Stock</x-atoms.table.th>
                <x-atoms.table.th class="text-right">Acciones</x-atoms.table.th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <x-atoms.table.tr>
                    <x-atoms.table.td>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($product->image) }}" alt="" class="size-10 rounded-lg border border-line object-cover">
                            <div class="leading-tight">
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-xs text-muted">{{ Str::limit($product->short_description, 50) }}</p>
                            </div>
                        </div>
                    </x-atoms.table.td>
                    <x-atoms.table.td class="font-mono text-xs text-muted">{{ $product->sku }}</x-atoms.table.td>
                    <x-atoms.table.td><x-molecules.table.price-cell :product="$product" /></x-atoms.table.td>
                    <x-atoms.table.td><x-molecules.table.stock-badge :product="$product" /></x-atoms.table.td>
                    <x-atoms.table.td>
                        <x-molecules.table.actions-cell
                            :show-url="route('products.show', $product)"
                            :edit-url="route('products.edit', $product)"
                            :delete-url="route('products.destroy', $product)"
                            :delete-message="'¿Eliminar el producto ' . $product->name . '?'" />
                    </x-atoms.table.td>
                </x-atoms.table.tr>
            @empty
                <x-atoms.table.empty-row :colspan="5">Todavía no hay productos en el catálogo.</x-atoms.table.empty-row>
            @endforelse
        </tbody>
    </x-atoms.table.table>
</x-atoms.ui.card>
