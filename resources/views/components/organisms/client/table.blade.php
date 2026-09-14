{{-- organismo: tabla de clientes empresa (3.1 listar) --}}
@props(['clients'])
<x-atoms.ui.card>
    <x-atoms.table.table>
        <thead>
            <tr>
                <x-atoms.table.th>Empresa</x-atoms.table.th>
                <x-atoms.table.th>RUT</x-atoms.table.th>
                <x-atoms.table.th>Rubro</x-atoms.table.th>
                <x-atoms.table.th>Contacto</x-atoms.table.th>
                <x-atoms.table.th class="text-right">Acciones</x-atoms.table.th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clients as $client)
                <x-atoms.table.tr>
                    <x-atoms.table.td>
                        <div class="flex items-center gap-3">
                            <x-atoms.ui.avatar :name="$client->business_name" />
                            <div class="leading-tight">
                                <p class="font-medium">{{ $client->business_name }}</p>
                                <p class="text-xs text-muted">{{ $client->phone }}</p>
                            </div>
                        </div>
                    </x-atoms.table.td>
                    <x-atoms.table.td class="text-muted">{{ $client->company_rut }}</x-atoms.table.td>
                    <x-atoms.table.td><x-atoms.ui.badge>{{ $client->business_sector }}</x-atoms.ui.badge></x-atoms.table.td>
                    <x-atoms.table.td>
                        <div class="leading-tight">
                            <p>{{ $client->contact_name }}</p>
                            <p class="text-xs text-muted">{{ $client->contact_email }}</p>
                        </div>
                    </x-atoms.table.td>
                    <x-atoms.table.td>
                        <x-molecules.table.actions-cell
                            :show-url="route('clients.show', $client)"
                            :edit-url="route('clients.edit', $client)"
                            :delete-url="route('clients.destroy', $client)"
                            :delete-message="'¿Eliminar al cliente ' . $client->business_name . '?'" />
                    </x-atoms.table.td>
                </x-atoms.table.tr>
            @empty
                <x-atoms.table.empty-row :colspan="5">Todavía no hay clientes registrados.</x-atoms.table.empty-row>
            @endforelse
        </tbody>
    </x-atoms.table.table>
</x-atoms.ui.card>
