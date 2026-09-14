{{-- atom base de iconos: viewBox 24, trazo del color del texto. cada icono solo pasa su "d" --}}
@props(['d'])
<svg {{ $attributes->merge(['class' => 'size-5 shrink-0']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}" />
</svg>
