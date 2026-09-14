{{-- molecula: cabecera de pagina: titulo + descripcion + boton de accion a la derecha (slot) --}}
@props(['title', 'description' => null])
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-end justify-between gap-4']) }}>
    <div class="space-y-1">
        <x-atoms.ui.heading :level="1">{{ $title }}</x-atoms.ui.heading>
        @if ($description)
            <x-atoms.ui.text>{{ $description }}</x-atoms.ui.text>
        @endif
    </div>
    @if (trim($slot))
        <div class="flex items-center gap-2">{{ $slot }}</div>
    @endif
</div>
