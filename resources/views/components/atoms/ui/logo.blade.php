{{-- atom: logo de VentasFix dentro de un circulo en relieve. en oscuro el png se invierte --}}
@props(['size' => 'size-10'])
<span {{ $attributes->merge(['class' => "neu-sm inline-flex $size shrink-0 items-center justify-center rounded-2xl p-1.5"]) }}>
    <img src="{{ asset('images/logo.png') }}" alt="VentasFix" class="size-full rounded-xl object-cover dark:invert">
</span>
