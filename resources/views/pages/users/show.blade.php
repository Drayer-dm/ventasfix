{{-- page: 1.2 ver usuario. desde aca se edita o elimina (1.5) --}}
<x-templates.app title="Usuarios">
    <x-molecules.ui.page-header title="Detalle de usuario">
        <x-atoms.form.link-button :href="route('users.index')" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
        <x-atoms.form.link-button :href="route('users.edit', $user)">
            <x-atoms.icon.pencil class="size-4" />
            Editar
        </x-atoms.form.link-button>
        <x-molecules.ui.confirm-delete :action="route('users.destroy', $user)" :message="'¿Eliminar al usuario ' . $user->first_name . ' ' . $user->last_name . '?'" />
    </x-molecules.ui.page-header>

    <x-organisms.user.detail-card :user="$user" />
</x-templates.app>
