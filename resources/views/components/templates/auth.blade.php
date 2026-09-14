{{-- template: layout de login. todo centrado, sin sidebar, toggle de tema arriba a la derecha --}}
@props(['title' => 'Iniciar sesión'])
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <x-templates.head :title="$title" />
</head>
<body class="min-h-full">
    <div class="absolute right-5 top-5">
        <x-atoms.ui.theme-toggle />
    </div>

    <main class="flex min-h-screen items-center justify-center p-4">
        {{ $slot }}
    </main>
</body>
</html>
