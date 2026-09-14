<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

//dashboard por API (requerimiento 4): cuantos usuarios, productos y clientes hay.
//un solo metodo → __invoke, la ruta apunta a la clase
#[OA\Tag(name: 'Dashboard', description: 'Requerimiento 4: resumen de la plataforma')]
class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    #[OA\Get(path: '/api/dashboard', tags: ['Dashboard'], summary: '4. Cantidad de usuarios, productos y clientes', security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Resumen: { users, products, clients }'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function __invoke(): JsonResponse
    {
        return $this->successResponse($this->dashboardService->getSummary(), 'Resumen del dashboard obtenido correctamente.');
    }
}
