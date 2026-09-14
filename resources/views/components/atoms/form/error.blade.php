{{-- atom: mensaje de error de un campo. UN solo contenedor pa los dos origenes:
     - back: @error pinta el mensaje del FormRequest cuando la validacion del servidor fallo
     - front: app.js escribe aca (data-error-for) cuando falla la validacion del navegador --}}
@props(['name'])
<p data-error-for="{{ $name }}" {{ $attributes->merge(['class' => 'mt-1 text-xs text-fg/80']) }} @unless ($errors->has($name)) hidden @endunless>{{ $errors->first($name) }}</p>
