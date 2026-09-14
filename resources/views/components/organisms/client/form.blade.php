{{-- organismo: form de cliente empresa (crear / editar). reglas del front = StoreClientRequest --}}
@props(['client' => null, 'action', 'method' => 'POST'])
<x-atoms.ui.card class="p-6 sm:p-8">
    <form method="POST" action="{{ $action }}" data-validate class="space-y-6">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        {{-- la empresa --}}
        <div class="space-y-5">
            <x-atoms.ui.heading :level="3">Empresa</x-atoms.ui.heading>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-molecules.form.field name="business_name" label="Razón social" :value="$client?->business_name" placeholder="Comercial Sur SpA" required maxlength="150" />
                <x-molecules.form.field name="company_rut" label="RUT empresa" :value="$client?->company_rut" placeholder="76.123.456-7" required maxlength="12"
                    pattern="(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]" data-pattern-message="Formato: 76.123.456-7 o 76123456-7." />
                <x-molecules.form.field name="business_sector" label="Rubro" :value="$client?->business_sector" placeholder="Retail" required maxlength="100" />
                <x-molecules.form.field name="phone" label="Teléfono" type="tel" :value="$client?->phone" placeholder="+56 9 1234 5678" required maxlength="20"
                    pattern="\+?[\d\s]{8,20}" data-pattern-message="Solo números y espacios, con + opcional al inicio." />
            </div>
            <x-molecules.form.field name="address" label="Dirección" :value="$client?->address" placeholder="Av. Siempre Viva 123, Santiago" required maxlength="255" />
        </div>

        {{-- la persona de contacto --}}
        <div class="space-y-5 border-t border-line pt-5">
            <x-atoms.ui.heading :level="3">Persona de contacto</x-atoms.ui.heading>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-molecules.form.field name="contact_name" label="Nombre" :value="$client?->contact_name" placeholder="Luis Soto" required maxlength="150" />
                <x-molecules.form.field name="contact_email" label="Correo" type="email" :value="$client?->contact_email" placeholder="luis@empresa.cl" required maxlength="150" />
            </div>
        </div>

        <x-molecules.ui.form-actions :cancel-url="$client ? route('clients.show', $client) : route('clients.index')" :label="$client ? 'Guardar cambios' : 'Crear cliente'" />
    </form>
</x-atoms.ui.card>
