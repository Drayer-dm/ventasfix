{{-- molecula: los 3 botones de cada fila: ver, editar, eliminar --}}
@props(['showUrl', 'editUrl', 'deleteUrl', 'deleteMessage' => null])
<div class="flex items-center justify-end gap-1">
    <x-atoms.form.link-button :href="$showUrl" variant="ghost" class="size-9 !p-0" title="Ver">
        <x-atoms.icon.eye class="size-4" />
    </x-atoms.form.link-button>
    <x-atoms.form.link-button :href="$editUrl" variant="ghost" class="size-9 !p-0" title="Editar">
        <x-atoms.icon.pencil class="size-4" />
    </x-atoms.form.link-button>
    <x-molecules.ui.confirm-delete :action="$deleteUrl" :message="$deleteMessage ?? '¿Seguro que quieres eliminar este registro?'" compact />
</div>
