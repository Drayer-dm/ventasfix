<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

//CRUD de usuarios por API (requerimiento 1). misma logica q el controller web, pero
//devuelve json con codigo http explicito: 200 ok, 201 creado, 404 no existe, 422 invalido.
//el 404 se maneja a mano (findById → null) pa q el error salga con nuestro envelope
#[OA\Tag(name: 'Usuarios', description: 'Requerimiento 1: usuarios del sistema')]
class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly UserService $userService)
    {
    }

    //1.1 listar todos → 200
    #[OA\Get(path: '/api/users', tags: ['Usuarios'], summary: '1.1 Listar todos los usuarios', security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Listado obtenido'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function index(): JsonResponse
    {
        return $this->successResponse($this->userService->getAll(), 'Listado de usuarios obtenido correctamente.');
    }

    //1.2 obtener por id → 200 · 404
    #[OA\Get(path: '/api/users/{id}', tags: ['Usuarios'], summary: '1.2 Obtener un usuario por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Usuario encontrado'),
            new OA\Response(response: 404, description: 'No existe un usuario con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (! $user) {
            return $this->errorResponse(__('No existe un usuario con el id :id.', ['id' => $id]), 404);
        }

        return $this->successResponse($user, 'Usuario encontrado.');
    }

    //1.3 agregar → 201 · 422 (la validacion es de StoreUserRequest, si falla no entra aca)
    #[OA\Post(path: '/api/users', tags: ['Usuarios'], summary: '1.3 Agregar un nuevo usuario', security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['rut', 'first_name', 'last_name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'rut', type: 'string', example: '12.345.678-5'),
                new OA\Property(property: 'first_name', type: 'string', example: 'Ana'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Pérez'),
                new OA\Property(property: 'email', type: 'string', example: 'ana@ventasfix.cl', description: 'Debe terminar en @ventasfix.cl'),
                new OA\Property(property: 'password', type: 'string', example: 'secreto123', description: 'Mínimo 8 caracteres, se guarda cifrada'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Usuario creado'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return $this->successResponse($user, 'Usuario creado correctamente.', 201);
    }

    //1.4 actualizar por id → 200 · 404 · 422. acepta PUT (todo) y PATCH (parcial, por el sometimes)
    #[OA\Put(path: '/api/users/{id}', tags: ['Usuarios'], summary: '1.4 Actualizar un usuario por su ID (PUT o PATCH)', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rut', type: 'string', example: '12.345.678-5'),
                new OA\Property(property: 'first_name', type: 'string', example: 'Anita'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Pérez'),
                new OA\Property(property: 'email', type: 'string', example: 'ana@ventasfix.cl'),
                new OA\Property(property: 'password', type: 'string', example: 'nuevaClave123', description: 'Opcional: si no viene, se mantiene la actual'),
            ]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Usuario actualizado'),
            new OA\Response(response: 404, description: 'No existe un usuario con ese id'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (! $user) {
            return $this->errorResponse(__('No existe un usuario con el id :id.', ['id' => $id]), 404);
        }

        $user = $this->userService->update($user, $request->validated());

        return $this->successResponse($user, 'Usuario actualizado correctamente.');
    }

    //1.5 eliminar por id → 200 · 404
    #[OA\Delete(path: '/api/users/{id}', tags: ['Usuarios'], summary: '1.5 Eliminar un usuario por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 3)],
        responses: [
            new OA\Response(response: 200, description: 'Usuario eliminado'),
            new OA\Response(response: 404, description: 'No existe un usuario con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        if (! $user) {
            return $this->errorResponse(__('No existe un usuario con el id :id.', ['id' => $id]), 404);
        }

        $this->userService->delete($user);

        return $this->successResponse(null, 'Usuario eliminado correctamente.');
    }
}
