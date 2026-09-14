<?php

namespace App\Providers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->ignoreStaleViteHotFile();

        //view composer: cada vez q se renderiza el sidebar le inyecta $navCounts
        //(cuantos users/products/clients hay) sacado del mismo DashboardService del dashboard.
        //asi ningun controller tiene q pasarlo a mano y el menu siempre esta al dia
        View::composer('components.organisms.layout.sidebar', function (ViewInstance $view): void {
            $view->with('navCounts', app(DashboardService::class)->getSummary());
        });
    }

    //cuando "npm run dev" se cierra mal deja public/hot y @vite apunta a un servidor q ya no existe
    //→ las paginas salen sin css. aca, solo en local, si el hot apunta a un puerto q no responde
    //lo borro y laravel usa public/build (lo compilado con npm run build)
    private function ignoreStaleViteHotFile(): void
    {
        $hotFile = public_path('hot');

        if (! $this->app->environment('local') || ! is_file($hotFile)) {
            return;
        }

        $url = parse_url(trim((string) file_get_contents($hotFile)));
        $host = trim($url['host'] ?? '127.0.0.1', '[]');
        $port = $url['port'] ?? 5173;

        $socket = @fsockopen($host, $port, $errno, $errstr, 0.2);

        if ($socket === false) {
            @unlink($hotFile);
        } else {
            fclose($socket);
        }
    }
}
