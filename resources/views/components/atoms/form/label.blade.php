{{-- atom: label de campo --}}
@props(['for'])
<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block text-sm font-medium']) }}>{{ $slot }}</label>
