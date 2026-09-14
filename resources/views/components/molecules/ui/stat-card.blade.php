{{-- molecula: tarjeta del dashboard: icono en relieve + numero grande + label + link al mantenedor --}}
@props(['label', 'value', 'href', 'icon'])
<x-atoms.ui.card class="p-6">
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-1">
            <x-atoms.ui.heading :level="3">{{ $label }}</x-atoms.ui.heading>
            <p class="text-4xl font-semibold tracking-tight tabular-nums">{{ number_format($value, 0, ',', '.') }}</p>
        </div>
        <span class="neu-sm inline-flex size-12 shrink-0 items-center justify-center rounded-2xl text-fg">
            <x-dynamic-component :component="'atoms.icon.' . $icon" class="size-6" />
        </span>
    </div>
    <a href="{{ $href }}" class="mt-5 inline-flex items-center gap-1 text-sm text-muted transition hover:text-fg">Ver detalle <span aria-hidden="true">→</span></a>
</x-atoms.ui.card>
