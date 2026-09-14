{{-- atom: boton redondo claro/oscuro. app.js muestra el icono q corresponde y guarda la eleccion --}}
<button type="button" data-theme-toggle {{ $attributes->merge(['class' => 'neu-sm inline-flex size-10 items-center justify-center rounded-full text-fg transition-[box-shadow] active:neu-inset-sm']) }} aria-label="Cambiar tema">
    <x-atoms.icon.moon data-theme-icon="dark" />
    <x-atoms.icon.sun data-theme-icon="light" hidden />
</button>
