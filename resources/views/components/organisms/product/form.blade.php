{{-- organismo: form de producto (crear / editar). enctype multipart pq lleva archivo.
     sale_price NO se pide: lo calcula el modelo con el 19%, aca solo se muestra un preview en vivo.
     reglas del front = StoreProductRequest (min:1 precio, min:0 stocks, umbrales coherentes en app.js) --}}
@props(['product' => null, 'action', 'method' => 'POST'])
<x-atoms.ui.card class="p-6 sm:p-8">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" data-validate class="space-y-6">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        {{-- datos basicos --}}
        <div class="grid gap-5 sm:grid-cols-2">
            <x-molecules.form.field name="sku" label="SKU" :value="$product?->sku" placeholder="TEC-001" required maxlength="50" />
            <x-molecules.form.field name="name" label="Nombre" :value="$product?->name" placeholder="Teclado mecánico" required maxlength="150" />
        </div>
        <x-molecules.form.field name="short_description" label="Descripción corta" :value="$product?->short_description" placeholder="Una línea para la tabla" required maxlength="255" />
        <x-molecules.form.textarea-field name="long_description" label="Descripción larga" :value="$product?->long_description" :rows="4" placeholder="Detalle completo del producto" required />
        <x-molecules.form.file-field name="image" label="Imagen del producto" :current="$product?->image" :required="$product === null"
            :help="$product ? 'Sube una nueva solo si quieres reemplazarla. JPG, PNG o WEBP, máx. 2 MB.' : 'JPG, PNG o WEBP, máx. 2 MB.'" />

        {{-- precio: el neto se escribe, el de venta se calcula en vivo (y en el back al guardar) --}}
        <div class="grid gap-5 sm:grid-cols-2">
            <x-molecules.form.field name="net_price" label="Precio neto (CLP)" type="number" :value="$product?->net_price" placeholder="10000" required min="1" max="4294967295" step="1" inputmode="numeric" />
            <div class="space-y-1.5">
                <x-atoms.form.label for="sale_price_preview">Precio de venta (IVA 19% incluido)</x-atoms.form.label>
                <p id="sale_price_preview" data-sale-price-preview="#net_price" class="neu-inset-sm rounded-xl px-4 py-2.5 text-sm font-medium tabular-nums">—</p>
                <x-atoms.ui.text class="text-xs">Se calcula solo: neto × 1,19.</x-atoms.ui.text>
            </div>
        </div>

        {{-- stocks: actual + los 3 umbrales (minimo <= bajo <= alto, app.js lo revisa en vivo) --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <x-molecules.form.field name="current_stock" label="Stock actual" type="number" :value="$product?->current_stock" placeholder="0" required min="0" max="4294967295" step="1" inputmode="numeric" />
            <x-molecules.form.field name="minimum_stock" label="Stock mínimo" type="number" :value="$product?->minimum_stock" placeholder="0" required min="0" max="4294967295" step="1" inputmode="numeric" />
            <x-molecules.form.field name="low_stock" label="Stock bajo" type="number" :value="$product?->low_stock" placeholder="0" required min="0" max="4294967295" step="1" inputmode="numeric" />
            <x-molecules.form.field name="high_stock" label="Stock alto" type="number" :value="$product?->high_stock" placeholder="0" required min="0" max="4294967295" step="1" inputmode="numeric" />
        </div>

        <x-molecules.ui.form-actions :cancel-url="$product ? route('products.show', $product) : route('products.index')" :label="$product ? 'Guardar cambios' : 'Crear producto'" />
    </form>
</x-atoms.ui.card>
