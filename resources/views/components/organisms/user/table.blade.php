{{-- organismo: tabla de usuarios (1.1 listar). $users viene de UserService::getAll() --}}
@props(['users'])
<x-atoms.ui.card>
    <x-atoms.table.table>
        <thead>
            <tr>
                <x-atoms.table.th>Usuario</x-atoms.table.th>
                <x-atoms.table.th>RUT</x-atoms.table.th>
                <x-atoms.table.th>Correo</x-atoms.table.th>
                <x-atoms.table.th>Creado</x-atoms.table.th>
                <x-atoms.table.th class="text-right">Acciones</x-atoms.table.th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <x-atoms.table.tr>
                    <x-atoms.table.td>
                        <div class="flex items-center gap-3">
                            <x-atoms.ui.avatar :name="$user->first_name . ' ' . $user->last_name" />
                            <span class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</span>
                        </div>
                    </x-atoms.table.td>
                    <x-atoms.table.td class="text-muted">{{ $user->rut }}</x-atoms.table.td>
                    <x-atoms.table.td>{{ $user->email }}</x-atoms.table.td>
                    <x-atoms.table.td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</x-atoms.table.td>
                    <x-atoms.table.td>
                        <x-molecules.table.actions-cell
                            :show-url="route('users.show', $user)"
                            :edit-url="route('users.edit', $user)"
                            :delete-url="route('users.destroy', $user)"
                            :delete-message="'¿Eliminar al usuario ' . $user->first_name . ' ' . $user->last_name . '?'" />
                    </x-atoms.table.td>
                </x-atoms.table.tr>
            @empty
                <x-atoms.table.empty-row :colspan="5">Todavía no hay usuarios registrados.</x-atoms.table.empty-row>
            @endforelse
        </tbody>
    </x-atoms.table.table>
</x-atoms.ui.card>
