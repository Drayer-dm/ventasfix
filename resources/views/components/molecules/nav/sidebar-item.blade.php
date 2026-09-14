{{-- molecula: item del menu: icono + texto + contador del modulo.
     route = a donde lleva (users.index). match = patron pa marcarlo activo (users.*).
     count = cuantos registros tiene el modulo (lo inyecta el view composer del sidebar).
     con el sidebar colapsado solo queda el icono, el texto y el contador se esconden --}}
@props(['route', 'icon', 'label', 'match' => null, 'count' => null])
@php
    $active = request()->routeIs($match ?? $route);
@endphp
<x-atoms.nav.link :href="route($route)" :active="$active" :label="$label">
    <x-dynamic-component :component="'atoms.icon.' . $icon" class="size-5 shrink-0" />
    <span class="flex-1 truncate collapsed:hidden">{{ $label }}</span>
    @if ($count !== null)
        <span class="rounded-full px-2 py-0.5 text-[11px] tabular-nums collapsed:hidden {{ $active ? 'bg-fg text-bg' : 'neu-inset-sm text-muted' }}">{{ $count }}</span>
    @endif
</x-atoms.nav.link>
