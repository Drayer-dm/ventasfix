{{-- molecula: pie de todos los forms: Guardar + Cancelar --}}
@props(['cancelUrl', 'label' => 'Guardar'])
<div class="flex items-center justify-end gap-2 border-t border-line pt-5">
    <x-atoms.form.link-button :href="$cancelUrl" variant="secondary">Cancelar</x-atoms.form.link-button>
    <x-atoms.form.button>{{ $label }}</x-atoms.form.button>
</div>
