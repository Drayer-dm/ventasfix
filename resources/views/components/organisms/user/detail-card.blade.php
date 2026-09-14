{{-- organismo: ficha de un usuario (1.2 obtener por id) --}}
@props(['user'])
<x-atoms.ui.card class="p-6">
    <div class="flex items-center gap-4 border-b border-line pb-5">
        <x-atoms.ui.avatar :name="$user->first_name . ' ' . $user->last_name" class="size-14 text-base" />
        <div>
            <x-atoms.ui.heading :level="2">{{ $user->first_name }} {{ $user->last_name }}</x-atoms.ui.heading>
            <x-atoms.ui.text>{{ $user->email }}</x-atoms.ui.text>
        </div>
    </div>

    <dl class="grid gap-5 pt-5 sm:grid-cols-2 lg:grid-cols-3">
        <x-molecules.ui.detail-item label="ID">#{{ $user->id }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="RUT">{{ $user->rut }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Correo">{{ $user->email }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Contraseña"><span class="text-muted">•••••••• (cifrada con bcrypt)</span></x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Creado">{{ $user->created_at->format('d/m/Y H:i') }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Actualizado">{{ $user->updated_at->format('d/m/Y H:i') }}</x-molecules.ui.detail-item>
    </dl>
</x-atoms.ui.card>
