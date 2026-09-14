{{-- molecula: form de eliminar. DELETE se simula con @method pq html solo sabe GET/POST.
     data-confirm lo lee app.js y pregunta antes de mandar --}}
@props(['action', 'message' => '¿Seguro que quieres eliminar este registro?', 'compact' => false])
<form method="POST" action="{{ $action }}" data-confirm="{{ $message }}" class="inline">
    @csrf
    @method('DELETE')
    @if ($compact)
        <x-atoms.form.button variant="ghost" class="size-9 !p-0" title="Eliminar">
            <x-atoms.icon.trash class="size-4" />
        </x-atoms.form.button>
    @else
        <x-atoms.form.button variant="danger">
            <x-atoms.icon.trash class="size-4" />
            Eliminar
        </x-atoms.form.button>
    @endif
</form>
