{{-- page: 404 web. laravel la usa sola cuando abort(404) o la ruta no existe.
     si hay sesion se muestra dentro del layout con sidebar, si no, centrada como el login --}}
@if (auth()->check())
    <x-templates.app title="No encontrado">
        <x-organisms.layout.error-state code="404" title="Página no encontrada" message="El registro o la ruta que buscas no existe o fue eliminado." />
    </x-templates.app>
@else
    <x-templates.auth title="No encontrado">
        <x-organisms.layout.error-state code="404" title="Página no encontrada" message="El registro o la ruta que buscas no existe o fue eliminado." />
    </x-templates.auth>
@endif
