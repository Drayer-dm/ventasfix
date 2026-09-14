{{-- page: dashboard (requerimiento 4). $summary lo manda DashboardController --}}
<x-templates.app title="Dashboard">
    <x-molecules.ui.page-header title="Resumen" description="Estado general de la plataforma VentasFix." />
    <x-organisms.dashboard.stats-grid :summary="$summary" />
</x-templates.app>
