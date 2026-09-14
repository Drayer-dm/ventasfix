{{-- page: 3.2 ver cliente. desde aca se edita o elimina (3.5) --}}
<x-templates.app title="Clientes">
    <x-molecules.ui.page-header title="Detalle de cliente">
        <x-atoms.form.link-button :href="route('clients.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
        <x-atoms.form.link-button :href="route('clients.edit', $client)">
            <x-atoms.icon.pencil class="size-4" />
            Editar
        </x-atoms.form.link-button>
        <x-molecules.ui.confirm-delete :action="route('clients.destroy', $client)" :message="'¿Eliminar al cliente ' . $client->business_name . '?'" />
    </x-molecules.ui.page-header>

    <x-organisms.client.detail-card :client="$client" />
</x-templates.app>
