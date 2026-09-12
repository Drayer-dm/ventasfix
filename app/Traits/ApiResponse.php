<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


 //Envelope uniforme para todas las respuestas JSON de la API.
 // Cualquier clase (controller, middleware, request) que haga `use ApiResponse`
 // responde con la misma estructura y el código HTTP repetido en el cuerpo.
trait ApiResponse
{
    protected function successResponse(mixed $data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'ok'      => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(string $message, int $code, ?array $errors = null): JsonResponse
    {
        return response()->json([
            'ok'      => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
