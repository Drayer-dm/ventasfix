{{-- organismo: formulario de login completo. POST a la misma ruta login --}}
<x-atoms.ui.card class="w-full max-w-sm p-8">
    <div class="mb-8 flex flex-col items-center gap-4 text-center">
        <x-atoms.ui.logo size="size-20" />
        <div>
            <x-atoms.ui.heading :level="2">Backoffice</x-atoms.ui.heading>
            <x-atoms.ui.text>Ingresa con tu cuenta @ventasfix.cl</x-atoms.ui.text>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" data-validate class="space-y-5">
        @csrf
        <x-molecules.form.field name="email" label="Correo electrónico" type="email" placeholder="nombre@ventasfix.cl" autocomplete="email" autofocus required />
        <x-molecules.form.field name="password" label="Contraseña" type="password" placeholder="••••••••" autocomplete="current-password" required />
        <x-atoms.form.button class="w-full">
            <x-atoms.icon.lock class="size-4" />
            Ingresar
        </x-atoms.form.button>
    </form>
</x-atoms.ui.card>
