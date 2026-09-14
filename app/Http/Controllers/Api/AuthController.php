<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

//SOBRE CADA METODO VAN LOS #[OA\...] DE SWAGGER, SIEMPRE ANTES DEL METODO (no adentro).
//este primer bloque es global: titulo de la doc, servidor y como se manda el token (bearer)
#[OA\Info(
    version: '1.0.0',
    title: 'API VentasFix',
    description: 'Microservicio de carro de compra de VentasFix: usuarios, productos, clientes y dashboard. Autenticación con JWT (Bearer).'
)]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: 'Servidor local')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Pega el access_token q devuelve /api/auth/login'
)]
#[OA\Tag(name: 'Auth', description: 'Registro, inicio y cierre de sesión (JWT)')]
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly UserService $userService)
    {
    }

    //registro de usuario (rubrica: "controlador Registro de Usuario con cifrado de clave").
    //usa el MISMO StoreUserRequest y UserService q el CRUD, la clave la hashea el modelo
    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        summary: 'Registrar un usuario nuevo',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['rut', 'first_name', 'last_name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'rut', type: 'string', example: '12.345.678-5'),
                new OA\Property(property: 'first_name', type: 'string', example: 'Ana'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Pérez'),
                new OA\Property(property: 'email', type: 'string', example: 'ana@ventasfix.cl'),
                new OA\Property(property: 'password', type: 'string', example: 'secreto123'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Usuario creado (password cifrada)'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
        ]
    )]
    public function register(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return $this->successResponse($user, 'Usuario creado correctamente.', 201);
    }

    //POST /api/auth/login → 200 · 401 · 422. si LoginRequest falla el 422 sale antes de entrar aca
    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        summary: 'Iniciar sesión y obtener el token JWT',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'test@ventasfix.cl'),
                new OA\Property(property: 'password', type: 'string', example: 'Test1234'),
            ]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Token entregado (access_token, token_type, expires_in)'),
            new OA\Response(response: 401, description: 'Credenciales inválidas'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $token = auth('api')->attempt($credentials);

        if (! $token) {
            return $this->errorResponse('Credenciales inválidas.', 401);
        }

        return $this->successResponse($this->tokenPayload($token), 'Inicio de sesión exitoso.');
    }

    //GET /api/auth/me → 200 · 401. devuelve al dueño del token, la password no sale pq esta hidden
    #[OA\Get(
        path: '/api/auth/me',
        tags: ['Auth'],
        summary: 'Usuario dueño del token',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Usuario autenticado'),
            new OA\Response(response: 401, description: 'Token ausente, inválido o expirado'),
        ]
    )]
    public function me(): JsonResponse
    {
        return $this->successResponse(auth('api')->user(), 'Usuario autenticado.');
    }

    //POST /api/auth/logout → 200 · 401. mete el token en la blacklist aunq no haya expirado
    #[OA\Post(
        path: '/api/auth/logout',
        tags: ['Auth'],
        summary: 'Cerrar sesión (invalida el token)',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Sesión cerrada'),
            new OA\Response(response: 401, description: 'Token ausente, inválido o expirado'),
        ]
    )]
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return $this->successResponse(null, 'Sesión cerrada exitosamente.');
    }

    private function tokenPayload(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60, // minutos → segundos
        ];
    }
}
