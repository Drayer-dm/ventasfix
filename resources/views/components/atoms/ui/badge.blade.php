{{-- atom: etiqueta chica hundida. tone = low | mid | high (los 3 grises del logo) --}}
@props(['tone' => 'low'])
@php
    $tones = [
        'low'  => 'neu-inset-sm text-fg',
        'mid'  => 'bg-badge-mid text-fg',
        'high' => 'bg-badge-high text-badge-high-fg',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ' . ($tones[$tone] ?? $tones['low'])]) }}>{{ $slot }}</span>
