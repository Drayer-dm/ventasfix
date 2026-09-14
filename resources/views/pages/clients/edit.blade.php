{{-- page: 3.4 actualizar cliente --}}
<x-templates.app title="Clientes">
    <x-molecules.ui.page-header title="Editar cliente" :description="$client->business_name">
        <x-atoms.form.link-button :href="route('clients.show', $client)" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.client.form :client="$client" :action="route('clients.update', $client)" method="PUT" />
</x-templates.app>
