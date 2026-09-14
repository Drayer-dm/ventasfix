{{-- atom: link del sidebar. activo = hundido en la superficie (como boton presionado),
     hover = en relieve. title muestra tooltip nativo cuando el sidebar esta colapsado --}}
@props(['href', 'active' => false, 'label' => null])
<a href="{{ $href }}" title="{{ $label }}" {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm transition-[box-shadow,color] duration-150 collapsed:justify-center collapsed:px-0 ' . ($active ? 'neu-inset-sm font-medium text-fg' : 'text-muted hover:neu-sm hover:text-fg')]) }} @if ($active) aria-current="page" @endif>
    {{ $slot }}
</a>
