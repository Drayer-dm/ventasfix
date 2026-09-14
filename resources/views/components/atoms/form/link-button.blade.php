{{-- atom: link con pinta de boton ("Nuevo", "Volver", "Editar"). mismas variantes q button --}}
@props(['href', 'variant' => 'primary'])
@php
    $variants = [
        'primary'   => 'neu-primary hover:bg-primary-hover active:shadow-none',
        'secondary' => 'neu-sm text-fg active:neu-inset-sm',
        'ghost'     => 'text-muted hover:neu-sm hover:text-fg active:neu-inset-sm',
    ];
@endphp
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-[box-shadow,background-color,color] duration-150 ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</a>
