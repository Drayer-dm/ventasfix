<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


 //Envelope uniforme para todas las respuestas JSON de la API.
 // Cualquier clase (controller, middleware, request) que haga `use ApiResponse`
 // responde con la misma estructura y el código HTTP repetido en el cuerpo.
 //
 // Multi-idioma: los mensajes se escriben en español en el código y acá pasan
 // por __(). Si el idioma activo (App\Http\Middleware\SetLocale) es 'en',
 // __() busca el texto como clave en lang/en.json y devuelve la traducción;
 // si no la encuentra (o el idioma es 'es') devuelve el mismo texto.
trait ApiResponse
{
    protected function successResponse(mixed $data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'ok'      => true,
            'code'    => $code,
            'message' => __($message),
            'data'    => $data,
        ], $code, [], JSON_UNESCAPED_UNICODE);
    }

    protected function errorResponse(string $message, int $code, ?array $errors = null): JsonResponse
    {
        return response()->json([
            'ok'      => false,
            'code'    => $code,
            'message' => __($message),
            'errors'  => $errors,
        ], $code, [], JSON_UNESCAPED_UNICODE);
    }
}
