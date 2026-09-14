{{-- molecula: par label/valor de las fichas de detalle (show) --}}
@props(['label'])
<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <dt class="text-xs font-medium uppercase tracking-wide text-muted">{{ $label }}</dt>
    <dd class="text-sm">{{ $slot }}</dd>
</div>
