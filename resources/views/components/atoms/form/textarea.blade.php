{{-- atom: textarea hundido --}}
@props(['name', 'value' => null, 'rows' => 4, 'error' => false])
<textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" {{ $attributes->merge(['class' => 'neu-inset-sm block w-full rounded-xl border-0 px-4 py-2.5 text-sm text-fg placeholder:text-muted/60 transition focus:outline-none focus:ring-2 focus:ring-fg/25 ' . ($error ? 'is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>
