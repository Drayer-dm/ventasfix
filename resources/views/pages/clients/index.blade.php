{{-- page: 3.1 listar clientes --}}
<x-templates.app title="Clientes">
    <x-molecules.ui.page-header title="Clientes" description="Empresas a las que vende VentasFix.">
        <x-atoms.form.link-button :href="route('clients.create')">
            <x-atoms.icon.plus class="size-4" />
            Nuevo cliente
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.client.table :clients="$clients" />
</x-templates.app>
