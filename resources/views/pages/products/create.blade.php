{{-- page: 2.3 agregar producto --}}
<x-templates.app title="Productos">
    <x-molecules.ui.page-header title="Nuevo producto" description="Todos los campos son obligatorios.">
        <x-atoms.form.link-button :href="route('products.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.product.form :action="route('products.store')" />
</x-templates.app>
