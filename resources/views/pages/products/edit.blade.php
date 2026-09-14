{{-- page: 2.4 actualizar producto --}}
<x-templates.app title="Productos">
    <x-molecules.ui.page-header title="Editar producto" :description="$product->name">
        <x-atoms.form.link-button :href="route('products.show', $product)" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.product.form :product="$product" :action="route('products.update', $product)" method="PUT" />
</x-templates.app>
