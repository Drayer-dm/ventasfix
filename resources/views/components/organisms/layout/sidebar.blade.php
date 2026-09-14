{{-- organismo: menu lateral neumorfico.
     - misma superficie q la pagina, con una sombra hacia la derecha (neu-edge-right)
     - desktop: fijo, colapsable a riel de iconos (data-sidebar="collapsed" en <html>, lo guarda app.js)
     - celular: cajon q entra deslizando con backdrop
     - $navCounts lo inyecta el view composer (AppServiceProvider) desde DashboardService --}}
<div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-black/40 md:hidden"></div>

<aside data-sidebar class="neu-edge-right fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col transition-[width,transform] duration-200 md:translate-x-0 collapsed:w-20">
    {{-- cabecera: logo + marca + colapsar --}}
    <div class="flex h-20 items-center gap-3 px-4 collapsed:justify-center collapsed:px-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-atoms.ui.logo size="size-11" />
            <div class="leading-tight collapsed:hidden">
                <x-atoms.ui.brand class="block" />
                <span class="text-[11px] uppercase tracking-wider text-muted">Backoffice</span>
            </div>
        </a>
        <div class="ml-auto flex items-center collapsed:hidden">
            <x-atoms.nav.collapse-button />
            <button type="button" data-sidebar-close class="text-muted md:hidden" aria-label="Cerrar menú">
                <x-atoms.icon.close />
            </button>
        </div>
    </div>

    {{-- cuando esta colapsado el boton de expandir va solo, centrado --}}
    <div class="hidden justify-center pb-2 collapsed:flex">
        <x-atoms.nav.collapse-button />
    </div>

    {{-- items del menu --}}
    <nav class="flex-1 space-y-1.5 overflow-y-auto px-3 py-2 collapsed:px-3">
        <p class="px-3 pb-2 pt-1 text-[11px] font-medium uppercase tracking-wider text-muted collapsed:hidden">Módulos</p>
        <x-molecules.nav.sidebar-item route="dashboard" icon="dashboard" label="Dashboard" />
        <x-molecules.nav.sidebar-item route="users.index" match="users.*" icon="users" label="Usuarios" :count="$navCounts['users'] ?? null" />
        <x-molecules.nav.sidebar-item route="products.index" match="products.*" icon="box" label="Productos" :count="$navCounts['products'] ?? null" />
        <x-molecules.nav.sidebar-item route="clients.index" match="clients.*" icon="building" label="Clientes" :count="$navCounts['clients'] ?? null" />
    </nav>

    {{-- pie: usuario logueado --}}
    <div class="p-4 collapsed:px-0">
        <x-molecules.nav.user-card :user="auth()->user()" />
    </div>
</aside>
