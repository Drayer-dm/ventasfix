{{-- organismo: form de usuario. sirve pa crear (user null, POST) y editar (user con datos, PUT).
     las reglas del front (required, maxlength, pattern...) son las MISMAS q StoreUserRequest:
     el navegador avisa al tiro y el back vuelve a validar por si acaso (nunca se confia solo en el front) --}}
@props(['user' => null, 'action', 'method' => 'POST'])
<x-atoms.ui.card class="p-6 sm:p-8">
    <form method="POST" action="{{ $action }}" data-validate class="space-y-5">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <x-molecules.form.field name="first_name" label="Nombre" :value="$user?->first_name" placeholder="Ana" required maxlength="100" />
            <x-molecules.form.field name="last_name" label="Apellido" :value="$user?->last_name" placeholder="Pérez" required maxlength="100" />
            <x-molecules.form.field name="rut" label="RUT" :value="$user?->rut" placeholder="12.345.678-9" required maxlength="12"
                pattern="(\d{1,2}\.\d{3}\.\d{3}|\d{7,8})-[\dkK]" data-pattern-message="Formato: 12.345.678-9 o 12345678-9." />
            <x-molecules.form.field name="email" label="Correo electrónico" type="email" :value="$user?->email" placeholder="nombre@ventasfix.cl" required maxlength="150"
                pattern=".+@ventasfix\.cl" data-pattern-message="Debe ser una cuenta @ventasfix.cl." help="Debe ser una cuenta @ventasfix.cl" />
        </div>

        <x-molecules.form.field
            name="password"
            label="Contraseña"
            type="password"
            placeholder="Mínimo 8 caracteres"
            autocomplete="new-password"
            minlength="8"
            :required="$user === null"
            :help="$user ? 'Déjala vacía para mantener la actual.' : null" />

        <x-molecules.ui.form-actions :cancel-url="$user ? route('users.show', $user) : route('users.index')" :label="$user ? 'Guardar cambios' : 'Crear usuario'" />
    </form>
</x-atoms.ui.card>
