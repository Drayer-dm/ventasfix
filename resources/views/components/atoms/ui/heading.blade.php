{{-- atom: titulos. level 1 = titulo de pagina, 2 = seccion, 3 = label de card --}}
@props(['level' => 1])
@php
    $classes = match ((int) $level) {
        1 => 'text-2xl font-semibold tracking-tight',
        2 => 'text-lg font-semibold tracking-tight',
        default => 'text-xs font-medium uppercase tracking-wide text-muted',
    };
@endphp
<h{{ $level }} {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</h{{ $level }}>
