{{-- organismo: barra superior plana (misma superficie, sin relieve): menu movil, titulo, toggle de tema --}}
@props(['title'])
<header class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button type="button" data-sidebar-open class="neu-sm inline-flex size-10 items-center justify-center rounded-full text-muted active:neu-inset-sm md:hidden" aria-label="Abrir menú">
            <x-atoms.icon.menu />
        </button>
        <div class="leading-tight">
            <p class="text-[11px] uppercase tracking-wider text-muted">VentasFix</p>
            <x-atoms.ui.heading :level="2" class="text-base">{{ $title }}</x-atoms.ui.heading>
        </div>
    </div>
    <x-atoms.ui.theme-toggle />
</header>
