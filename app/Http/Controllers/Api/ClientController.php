<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Services\ClientService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

//CRUD de clientes empresa por API (requerimiento 3)
#[OA\Tag(name: 'Clientes', description: 'Requerimiento 3: clientes empresa')]
class ClientController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly ClientService $clientService)
    {
    }

    //3.1 listar todos → 200
    #[OA\Get(path: '/api/clients', tags: ['Clientes'], summary: '3.1 Listar todos los clientes', security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Listado obtenido'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function index(): JsonResponse
    {
        return $this->successResponse($this->clientService->getAll(), 'Listado de clientes obtenido correctamente.');
    }

    //3.2 obtener por id → 200 · 404
    #[OA\Get(path: '/api/clients/{id}', tags: ['Clientes'], summary: '3.2 Obtener un cliente por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Cliente encontrado'),
            new OA\Response(response: 404, description: 'No existe un cliente con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function show(int $id): JsonResponse
    {
        $client = $this->clientService->findById($id);

        if (! $client) {
            return $this->errorResponse(__('No existe un cliente con el id :id.', ['id' => $id]), 404);
        }

        return $this->successResponse($client, 'Cliente encontrado.');
    }

    //3.3 agregar → 201 · 422
    #[OA\Post(path: '/api/clients', tags: ['Clientes'], summary: '3.3 Agregar un nuevo cliente', security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['company_rut', 'business_sector', 'business_name', 'phone', 'address', 'contact_name', 'contact_email'],
            properties: [
                new OA\Property(property: 'company_rut', type: 'string', example: '78.111.222-3'),
                new OA\Property(property: 'business_sector', type: 'string', example: 'Minería'),
                new OA\Property(property: 'business_name', type: 'string', example: 'Minera del Norte S.A.'),
                new OA\Property(property: 'phone', type: 'string', example: '+56 9 8765 4321'),
                new OA\Property(property: 'address', type: 'string', example: 'Ruta 5 Norte km 12, Antofagasta'),
                new OA\Property(property: 'contact_name', type: 'string', example: 'Carla Muñoz'),
                new OA\Property(property: 'contact_email', type: 'string', example: 'cmunoz@mineranorte.cl'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Cliente creado'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientService->create($request->validated());

        return $this->successResponse($client, 'Cliente creado correctamente.', 201);
    }

    //3.4 actualizar por id → 200 · 404 · 422 (PUT o PATCH parcial)
    #[OA\Put(path: '/api/clients/{id}', tags: ['Clientes'], summary: '3.4 Actualizar un cliente por su ID (PUT o PATCH)', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'company_rut', type: 'string', example: '76.123.456-7'),
                new OA\Property(property: 'business_sector', type: 'string', example: 'Retail y distribución'),
                new OA\Property(property: 'business_name', type: 'string', example: 'Comercial Sur SpA'),
                new OA\Property(property: 'phone', type: 'string', example: '+56 9 1234 5678'),
                new OA\Property(property: 'address', type: 'string', example: 'Av. Libertador 1234, Santiago'),
                new OA\Property(property: 'contact_name', type: 'string', example: 'Luis Soto'),
                new OA\Property(property: 'contact_email', type: 'string', example: 'luis.soto@comercialsur.cl'),
            ]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Cliente actualizado'),
            new OA\Response(response: 404, description: 'No existe un cliente con ese id'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function update(UpdateClientRequest $request, int $id): JsonResponse
    {
        $client = $this->clientService->findById($id);

        if (! $client) {
            return $this->errorResponse(__('No existe un cliente con el id :id.', ['id' => $id]), 404);
        }

        $client = $this->clientService->update($client, $request->validated());

        return $this->successResponse($client, 'Cliente actualizado correctamente.');
    }

    //3.5 eliminar por id → 200 · 404
    #[OA\Delete(path: '/api/clients/{id}', tags: ['Clientes'], summary: '3.5 Eliminar un cliente por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 2)],
        responses: [
            new OA\Response(response: 200, description: 'Cliente eliminado'),
            new OA\Response(response: 404, description: 'No existe un cliente con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function destroy(int $id): JsonResponse
    {
        $client = $this->clientService->findById($id);

        if (! $client) {
            return $this->errorResponse(__('No existe un cliente con el id :id.', ['id' => $id]), 404);
        }

        $this->clientService->delete($client);

        return $this->successResponse(null, 'Cliente eliminado correctamente.');
    }
}
