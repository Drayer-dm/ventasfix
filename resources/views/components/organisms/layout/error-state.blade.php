{{-- organismo: pantalla de error (404, 419, etc): codigo grande hundido + mensaje + boton pa volver --}}
@props(['code', 'title', 'message'])
<x-atoms.ui.card class="mx-auto w-full max-w-md p-10 text-center">
    <span class="neu-inset mx-auto mb-6 inline-flex size-24 items-center justify-center rounded-3xl text-3xl font-semibold tabular-nums text-muted">{{ $code }}</span>
    <x-atoms.ui.heading :level="2">{{ $title }}</x-atoms.ui.heading>
    <x-atoms.ui.text class="mt-2">{{ $message }}</x-atoms.ui.text>
    <div class="mt-8 flex justify-center gap-2">
        <x-atoms.form.link-button :href="auth()->check() ? route('dashboard') : route('login')">
            <x-atoms.icon.back class="size-4" />
            {{ auth()->check() ? 'Ir al dashboard' : 'Ir al login' }}
        </x-atoms.form.link-button>
    </div>
</x-atoms.ui.card>
