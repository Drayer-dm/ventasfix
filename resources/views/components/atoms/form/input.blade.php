{{-- atom: input hundido en la superficie. old() recupera lo escrito si el back devolvio al form.
     los atributos de validacion (required, minlength, pattern, min...) llegan por $attributes
     y los usa el navegador + app.js pa validar en el front ANTES de mandar --}}
@props(['name', 'type' => 'text', 'value' => null, 'error' => false])
<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
    {{ $attributes->merge(['class' => 'neu-inset-sm block w-full rounded-xl border-0 px-4 py-2.5 text-sm text-fg placeholder:text-muted/60 transition focus:outline-none focus:ring-2 focus:ring-fg/25 ' . ($error ? 'is-invalid' : '')]) }}
>
