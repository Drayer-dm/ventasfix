<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de la API — VentasFix
|--------------------------------------------------------------------------
|
| bootstrap/app.php carga este archivo con ->withRouting(api: ...), lo que
| antepone el prefijo /api a todas las rutas de acá automáticamente.
|
*/
use App\Http\Controllers\Api\AuthController;

// Autenticación → /api/auth/...
Route::prefix('auth')->group(function () {
    // Pública: es la única forma de conseguir un token.
    Route::post('login', [AuthController::class, 'login']);

    // Protegidas: exigen "Authorization: Bearer <token>".
    Route::middleware('jwt.verify')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
