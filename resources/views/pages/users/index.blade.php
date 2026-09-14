{{-- page: 1.1 listar usuarios --}}
<x-templates.app title="Usuarios">
    <x-molecules.ui.page-header title="Usuarios" description="Trabajadores con acceso al backoffice.">
        <x-atoms.form.link-button :href="route('users.create')">
            <x-atoms.icon.plus class="size-4" />
            Nuevo usuario
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.user.table :users="$users" />
</x-templates.app>
