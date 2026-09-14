{{-- atom: select hundido. options = ['valor' => 'Texto'] --}}
@props(['name', 'options' => [], 'value' => null, 'error' => false])
<select name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'neu-inset-sm block w-full rounded-xl border-0 px-4 py-2.5 text-sm text-fg transition focus:outline-none focus:ring-2 focus:ring-fg/25 ' . ($error ? 'is-invalid' : '')]) }}>
    @foreach ($options as $optionValue => $label)
        <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $label }}</option>
    @endforeach
</select>
