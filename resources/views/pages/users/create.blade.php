{{-- page: 1.3 agregar usuario --}}
<x-templates.app title="Usuarios">
    <x-molecules.ui.page-header title="Nuevo usuario" description="Todos los campos son obligatorios.">
        <x-atoms.form.link-button :href="route('users.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.user.form :action="route('users.store')" />
</x-templates.app>
