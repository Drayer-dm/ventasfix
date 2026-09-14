{{-- atom: fila unica cuando no hay registros --}}
@props(['colspan' => 1])
<tr class="border-t border-line">
    <td colspan="{{ $colspan }}" class="px-4 py-10 text-center text-sm text-muted">{{ $slot }}</td>
</tr>
