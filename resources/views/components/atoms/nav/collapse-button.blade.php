{{-- atom: boton pa colapsar/expandir el sidebar (solo desktop). app.js guarda el estado --}}
<button type="button" data-sidebar-collapse title="Colapsar menú" {{ $attributes->merge(['class' => 'neu-sm hidden size-8 items-center justify-center rounded-full text-muted transition-[box-shadow,color,transform] hover:text-fg active:neu-inset-sm md:inline-flex collapsed:rotate-180']) }} aria-label="Colapsar menú">
    <x-atoms.icon.back class="size-4" />
</button>
