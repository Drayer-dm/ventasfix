<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;


class JwtMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        try
        {
            //parseToken() es el que lee el header Authorization; el authenticate() valida
            // firma y expiracion, y carga el user dicho id viene en "sub"
            $user = JWTAuth::parseToken()->authenticate();

            if(!$user)
            {
                return $this->errorResponse('El usuario del token ya no existe.', 401);
            }
        }catch(TokenExpiredException $e)
        {
            return $this->errorResponse('El token expiró.', 401);
        }catch(TokenInvalidException $e)
        {
            return $this->errorResponse('El token es inválido.', 401);
        }catch(JWTException $e)
        {
            return $this->errorResponse('Token ausente.', 401);
        }catch(\Exception $e)
        {
            return $this->errorResponse('Error al procesar el token.', 401);
        }
        return $next($request);
    }
}
