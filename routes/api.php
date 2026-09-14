<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de la API — VentasFix
|--------------------------------------------------------------------------
| bootstrap/app.php carga este archivo con ->withRouting(api: ...), lo que
| antepone el prefijo /api a todas las rutas de acá automáticamente.
| doc interactiva: /api/documentation (swagger)
*/

// {id} solo acepta numeros: /api/users/abc no matchea ninguna ruta → 404 json,
// en vez de llegar al controller con "abc" en un int $id y reventar con 500
Route::pattern('id', '[0-9]+');

// Autenticación → /api/auth/...
Route::prefix('auth')->group(function () {
    // publicas: registro y login son la unica forma de conseguir un token
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // protegidas: exigen "Authorization: Bearer <token>"
    Route::middleware('jwt.verify')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// todo el crud exige token (nota 5 del enunciado: "el consumo del api debe tener autenticacion").
// las rutas van explicitas (no apiResource) pa q se lea de corrido cada requerimiento
Route::middleware('jwt.verify')->group(function () {

    // 1. usuarios
    Route::get('users', [UserController::class, 'index']);            // 1.1 listar        → 200
    Route::get('users/{id}', [UserController::class, 'show']);        // 1.2 por id        → 200 · 404
    Route::post('users', [UserController::class, 'store']);           // 1.3 agregar       → 201 · 422
    Route::match(['put', 'patch'], 'users/{id}', [UserController::class, 'update']); // 1.4 → 200 · 404 · 422
    Route::delete('users/{id}', [UserController::class, 'destroy']);  // 1.5 eliminar      → 200 · 404

    // 2. productos
    Route::get('products', [ProductController::class, 'index']);           // 2.1
    Route::get('products/{id}', [ProductController::class, 'show']);       // 2.2
    Route::post('products', [ProductController::class, 'store']);          // 2.3
    Route::match(['put', 'patch'], 'products/{id}', [ProductController::class, 'update']); // 2.4
    Route::delete('products/{id}', [ProductController::class, 'destroy']); // 2.5

    // 3. clientes
    Route::get('clients', [ClientController::class, 'index']);           // 3.1
    Route::get('clients/{id}', [ClientController::class, 'show']);       // 3.2
    Route::post('clients', [ClientController::class, 'store']);          // 3.3
    Route::match(['put', 'patch'], 'clients/{id}', [ClientController::class, 'update']); // 3.4
    Route::delete('clients/{id}', [ClientController::class, 'destroy']); // 3.5

    // 4. dashboard
    Route::get('dashboard', DashboardController::class);                 // 4.1 - 4.3
});
