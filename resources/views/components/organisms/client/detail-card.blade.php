{{-- organismo: ficha de un cliente empresa (3.2 obtener por id) --}}
@props(['client'])
<x-atoms.ui.card class="p-6">
    <div class="flex items-center gap-4 border-b border-line pb-5">
        <x-atoms.ui.avatar :name="$client->business_name" class="size-14 text-base" />
        <div>
            <x-atoms.ui.heading :level="2">{{ $client->business_name }}</x-atoms.ui.heading>
            <x-atoms.ui.text>{{ $client->company_rut }} · {{ $client->business_sector }}</x-atoms.ui.text>
        </div>
    </div>

    <dl class="grid gap-5 pt-5 sm:grid-cols-2 lg:grid-cols-3">
        <x-molecules.ui.detail-item label="ID">#{{ $client->id }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Teléfono">{{ $client->phone }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Dirección">{{ $client->address }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Persona de contacto">{{ $client->contact_name }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Correo de contacto">{{ $client->contact_email }}</x-molecules.ui.detail-item>
        <x-molecules.ui.detail-item label="Creado">{{ $client->created_at->format('d/m/Y H:i') }}</x-molecules.ui.detail-item>
    </dl>
</x-atoms.ui.card>
