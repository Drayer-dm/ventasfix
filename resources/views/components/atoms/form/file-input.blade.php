{{-- atom: input de archivo. el boton nativo se pisa con las clases file:* (en relieve).
     data-max-size lo lee app.js pa validar peso y tipo antes de subir --}}
@props(['name', 'accept' => 'image/jpeg,image/png,image/webp', 'error' => false])
<input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}" {{ $attributes->merge(['class' => 'block w-full rounded-xl text-sm text-muted file:neu-sm file:mr-3 file:rounded-xl file:border-0 file:px-4 file:py-2 file:text-sm file:font-medium file:text-fg hover:file:text-fg ' . ($error ? 'is-invalid p-1' : '')]) }}>
