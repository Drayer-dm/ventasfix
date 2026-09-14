{{-- template: layout de todas las paginas logueadas. sidebar + topbar + contenido --}}
@props(['title' => 'Backoffice'])
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <x-templates.head :title="$title" />
</head>
<body class="min-h-full">
    <x-organisms.layout.sidebar />

    {{-- md:pl-64 deja el espacio del sidebar fijo; colapsado se achica a pl-20 --}}
    <div class="min-h-screen transition-[padding] duration-200 md:pl-64 collapsed:pl-20">
        <x-organisms.layout.topbar :title="$title" />
        <main class="mx-auto max-w-7xl space-y-6 px-4 pb-10 pt-2 sm:px-6 lg:px-8">
            <x-organisms.layout.flash-messages />
            {{ $slot }}
        </main>
    </div>
</body>
</html>
