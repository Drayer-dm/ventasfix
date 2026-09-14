{{-- organismo: las 3 tarjetas del dashboard (requerimiento 4). $summary viene de DashboardService --}}
@props(['summary'])
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-molecules.ui.stat-card label="Usuarios" :value="$summary['users']" :href="route('users.index')" icon="users" />
    <x-molecules.ui.stat-card label="Productos" :value="$summary['products']" :href="route('products.index')" icon="box" />
    <x-molecules.ui.stat-card label="Clientes" :value="$summary['clients']" :href="route('clients.index')" icon="building" />
</div>
