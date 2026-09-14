{{-- organismo: mensajes flash de la sesion (los ->with('success', ...) de los controllers) --}}
@if (session('success'))
    <x-molecules.ui.alert tone="success">{{ session('success') }}</x-molecules.ui.alert>
@endif
@if (session('error'))
    <x-molecules.ui.alert tone="error">{{ session('error') }}</x-molecules.ui.alert>
@endif
