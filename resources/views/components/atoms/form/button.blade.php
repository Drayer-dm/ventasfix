{{-- atom: boton. variant = primary (relleno negro/blanco) | secondary (relieve) | danger | ghost.
     al presionar (active) el relieve se hunde: eso es lo q da la sensacion de boton fisico --}}
@props(['variant' => 'primary', 'type' => 'submit'])
@php
    $variants = [
        'primary'   => 'neu-primary hover:bg-primary-hover active:shadow-none',
        'secondary' => 'neu-sm text-fg hover:text-fg active:neu-inset-sm',
        'danger'    => 'neu-sm text-fg hover:bg-fg hover:text-bg active:neu-inset-sm',
        'ghost'     => 'text-muted hover:neu-sm hover:text-fg active:neu-inset-sm',
    ];
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-[box-shadow,background-color,color] duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-fg/30 disabled:opacity-50 ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</button>
