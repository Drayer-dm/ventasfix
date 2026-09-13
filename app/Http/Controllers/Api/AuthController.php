<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    //post en caso de que el loginrequest falle, el 422 deberia salir antes aqui
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $token = auth('api')->attempt($credentials);

        if(!$token)
        {
            return $this->errorResponse('Credenciales inválidas.', 401);
        }

        return $this->successResponse($this->tokenPayload($token), 'Inicio de sesión exitoso.');
    }
    //esto e un get ->200-401 devuelve al dueno del token, la contrasena no sale pq esta hidden
    public function me(): JsonResponse
    {
        return $this->successResponse(auth('api')->user(), 'Usuario autenticado.');
    }

    //mete el token en la blacklist aunq no haya expirado, yanosirve w
    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->successResponse(null, 'Sesión cerrada exitosamente.');
    }


    private function tokenPayload(string $token): array
    {
        return
        [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60, // minutos → segundos
        ];
    }
}
