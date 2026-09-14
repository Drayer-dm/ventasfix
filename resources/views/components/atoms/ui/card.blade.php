{{-- atom: panel en relieve. TODO lo q "sube" de la superficie usa esto (cards, tablas, forms) --}}
<div {{ $attributes->merge(['class' => 'neu rounded-3xl']) }}>
    {{ $slot }}
</div>
