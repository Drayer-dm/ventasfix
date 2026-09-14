{{-- molecula: label + input + ayuda + error = el campo completo.
     TODO lo q no sea prop (required, minlength, pattern, min, autocomplete...) se lo paso al input:
     son los atributos q usa el navegador y app.js pa validar en el front --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null, 'wrapperClass' => ''])
<div class="space-y-1.5 {{ $wrapperClass }}">
    <x-atoms.form.label :for="$name">{{ $label }}</x-atoms.form.label>
    <x-atoms.form.input :name="$name" :type="$type" :value="$value" :error="$errors->has($name)" {{ $attributes }} />
    @if ($help)
        <x-atoms.ui.text class="text-xs">{{ $help }}</x-atoms.ui.text>
    @endif
    <x-atoms.form.error :name="$name" />
</div>
