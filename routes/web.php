<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas WEB — Backoffice VentasFix
|--------------------------------------------------------------------------
| el grupo "web" (sesion + csrf) se aplica solo por estar en este archivo.
| las rutas de la api (json + jwt) estan en routes/api.php
*/

//raiz: si ya esta logueado al dashboard, si no al login
Route::get('/', fn () => redirect()->route(Auth::check() ? 'dashboard' : 'login'));

//solo pa NO logueados (guest). si ya tiene sesion y entra a /login lo mando al dashboard
Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

//todo lo demas exige sesion (auth). sin sesion → redirect a login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

    //requerimiento 4
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //requerimientos 1, 2 y 3: resource crea las 7 rutas de cada mantenedor
    //(index, create, store, show, edit, update, destroy).
    //parameters(['users' => 'id']) hace q la url sea /users/{id} en vez de /users/{user},
    //q es lo q leen los FormRequest con $this->route('id')
    Route::resource('users', UserController::class)->parameters(['users' => 'id']);
    Route::resource('products', ProductController::class)->parameters(['products' => 'id']);
    Route::resource('clients', ClientController::class)->parameters(['clients' => 'id']);
});
