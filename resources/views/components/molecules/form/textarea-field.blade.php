{{-- molecula: label + textarea + error. los atributos extra (required, maxlength...) van al textarea --}}
@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null, 'wrapperClass' => ''])
<div class="space-y-1.5 {{ $wrapperClass }}">
    <x-atoms.form.label :for="$name">{{ $label }}</x-atoms.form.label>
    <x-atoms.form.textarea :name="$name" :value="$value" :rows="$rows" :error="$errors->has($name)" {{ $attributes }} />
    @if ($help)
        <x-atoms.ui.text class="text-xs">{{ $help }}</x-atoms.ui.text>
    @endif
    <x-atoms.form.error :name="$name" />
</div>
