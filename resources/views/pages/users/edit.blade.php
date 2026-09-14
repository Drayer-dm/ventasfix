{{-- page: 1.4 actualizar usuario --}}
<x-templates.app title="Usuarios">
    <x-molecules.ui.page-header title="Editar usuario" :description="$user->first_name . ' ' . $user->last_name">
        <x-atoms.form.link-button :href="route('users.show', $user)" variant="secondary">
            <x-atoms.icon.back class="size-4" />
            Volver
        </x-atoms.form.link-button>
    </x-molecules.ui.page-header>

    <x-organisms.user.form :user="$user" :action="route('users.update', $user)" method="PUT" />
</x-templates.app>
