{{-- molecula: aviso hundido en la superficie (flash de exito, error, etc). tone = success | error --}}
@props(['tone' => 'success'])
<div role="alert" {{ $attributes->merge(['class' => 'neu-inset-sm flex items-start gap-3 rounded-2xl px-4 py-3 text-sm']) }}>
    @if ($tone === 'error')
        <x-atoms.icon.alert class="mt-0.5 size-4" />
    @else
        <x-atoms.icon.check class="mt-0.5 size-4" />
    @endif
    <div class="space-y-0.5">{{ $slot }}</div>
</div>
