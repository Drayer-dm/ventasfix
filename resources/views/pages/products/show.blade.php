{{-- page: 2.2 ver producto. desde aca se edita o elimina (2.5) --}}
<x-templates.app title="Productos">
    <x-molecules.ui.page-header title="Detalle de producto">
        <x-atoms.form.link-button :href="route('products.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
        <x-atoms.form.link-button :href="route('products.edit', $product)">
            <x-atoms.icon.pencil class="size-4" />
            Editar
        </x-atoms.form.link-button>
        <x-molecules.ui.confirm-delete :action="route('products.destroy', $product)" :message="'¿Eliminar el producto ' . $product->name . '?'" />
    </x-molecules.ui.page-header>

    <x-organisms.product.detail-card :product="$product" />
</x-templates.app>
