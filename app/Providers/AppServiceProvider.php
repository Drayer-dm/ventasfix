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
        //view composer: cada vez q se renderiza el sidebar le inyecta $navCounts
        //(cuantos users/products/clients hay) sacado del mismo DashboardService del dashboard.
        //asi ningun controller tiene q pasarlo a mano y el menu siempre esta al dia
        View::composer('components.organisms.layout.sidebar', function (ViewInstance $view): void {
            $view->with('navCounts', app(DashboardService::class)->getSummary());
        });
    }
}
