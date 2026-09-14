{{-- atom: fila de datos. linea entre filas y hover apenas mas oscuro --}}
<tr {{ $attributes->merge(['class' => 'border-t border-line transition hover:bg-fg/[0.03]']) }}>{{ $slot }}</tr>
