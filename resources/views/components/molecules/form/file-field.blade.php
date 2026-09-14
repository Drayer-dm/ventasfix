{{-- molecula: label + preview + file-input + error.
     el preview muestra la imagen actual (editar) y app.js lo reemplaza al elegir un archivo nuevo.
     data-max-size (bytes) y accept los usa app.js pa rechazar antes de subir --}}
@props(['name', 'label', 'current' => null, 'help' => null, 'maxSize' => 2097152, 'wrapperClass' => ''])
<div class="space-y-2 {{ $wrapperClass }}">
    <x-atoms.form.label :for="$name">{{ $label }}</x-atoms.form.label>
    <div class="flex items-start gap-4">
        <span class="neu-inset-sm flex size-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl text-muted">
            <img id="{{ $name }}_preview" src="{{ $current ? asset($current) : '' }}" alt="" class="size-full object-cover" @unless ($current) hidden @endunless>
            <x-atoms.icon.image id="{{ $name }}_placeholder" class="size-6" @if ($current) hidden @endif />
        </span>
        <div class="flex-1 space-y-1.5">
            <x-atoms.form.file-input :name="$name" :error="$errors->has($name)" :data-max-size="$maxSize" data-preview="#{{ $name }}_preview" {{ $attributes }} />
            @if ($help)
                <x-atoms.ui.text class="text-xs">{{ $help }}</x-atoms.ui.text>
            @endif
            <x-atoms.form.error :name="$name" />
        </div>
    </div>
</div>
