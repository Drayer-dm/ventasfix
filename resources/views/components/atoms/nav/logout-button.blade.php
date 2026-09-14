{{-- atom: cerrar sesion. es un form POST pq el logout no puede ser un GET (csrf + no cachear).
     boton redondo en relieve, solo icono --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" title="Cerrar sesión" {{ $attributes->merge(['class' => 'neu-sm inline-flex size-10 items-center justify-center rounded-full text-muted transition-[box-shadow,color] hover:text-fg active:neu-inset-sm']) }} aria-label="Cerrar sesión">
        <x-atoms.icon.logout />
    </button>
</form>
