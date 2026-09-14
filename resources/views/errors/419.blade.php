{{-- page: 419 = token csrf vencido (formulario abierto mucho rato). se pide reintentar --}}
@if (auth()->check())
    <x-templates.app title="Sesión expirada">
        <x-organisms.layout.error-state code="419" title="La página expiró" message="Estuviste mucho tiempo sin actividad. Vuelve a intentarlo." />
    </x-templates.app>
@else
    <x-templates.auth title="Sesión expirada">
        <x-organisms.layout.error-state code="419" title="La página expiró" message="Estuviste mucho tiempo sin actividad. Vuelve a iniciar sesión." />
    </x-templates.auth>
@endif
