{{-- atom: tabla base. el div de afuera da scroll horizontal en pantallas chicas --}}
<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-left text-sm']) }}>
        {{ $slot }}
    </table>
</div>
