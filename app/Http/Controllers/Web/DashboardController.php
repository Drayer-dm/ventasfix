<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

//dashboard web (requerimiento 4). un solo metodo → __invoke, la ruta apunta a la clase
class DashboardController extends Controller
{
    //laravel inyecta el service solo (container), no hay q hacer new
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function __invoke(): View
    {
        return view('pages.dashboard.index', [
            'summary' => $this->dashboardService->getSummary(),
        ]);
    }
}
