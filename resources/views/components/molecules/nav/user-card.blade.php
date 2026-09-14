{{-- molecula: tarjeta del usuario logueado al pie del sidebar: avatar + nombre + email + logout.
     colapsado: solo el avatar y el boton de salir --}}
@props(['user'])
<div {{ $attributes->merge(['class' => 'flex items-center gap-3 collapsed:flex-col collapsed:gap-2']) }}>
    <x-atoms.ui.avatar :name="$user->first_name . ' ' . $user->last_name" />
    <div class="min-w-0 flex-1 leading-tight collapsed:hidden">
        <p class="truncate text-sm font-medium">{{ $user->first_name }} {{ $user->last_name }}</p>
        <p class="truncate text-xs text-muted">{{ $user->email }}</p>
    </div>
    <x-atoms.nav.logout-button />
</div>
