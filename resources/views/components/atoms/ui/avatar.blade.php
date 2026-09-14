{{-- atom: circulo en relieve con iniciales, no hay foto de perfil --}}
@props(['name'])
@php
    $initials = collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');
@endphp
<span {{ $attributes->merge(['class' => 'neu-sm inline-flex size-10 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-fg']) }}>{{ $initials }}</span>
