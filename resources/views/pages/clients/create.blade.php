{{-- page: 3.3 agregar cliente --}}
<x-templates.app title="Clientes">
    <x-molecules.ui.page-header title="Nuevo cliente" description="Todos los campos son obligatorios.">
        <x-atoms.form.link-button :href="route('clients.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.client.form :action="route('clients.store')" />
</x-templates.app>
