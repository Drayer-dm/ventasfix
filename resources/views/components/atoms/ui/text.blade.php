{{-- atom: parrafo secundario (descripciones, ayudas) --}}
<p {{ $attributes->merge(['class' => 'text-sm text-muted']) }}>{{ $slot }}</p>
