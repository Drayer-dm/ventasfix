{{-- page: 2.1 listar productos --}}
<x-templates.app title="Productos">
    <x-molecules.ui.page-header title="Productos" description="Catálogo con precios en CLP, IVA incluido.">
        <x-atoms.form.link-button :href="route('products.create')">
            <x-atoms.icon.plus class="size-4" />
            Nuevo producto
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.product.table :products="$products" />
</x-templates.app>
