<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

//login/logout de la WEB. aca se usa sesion (guard web), no jwt: el jwt es pa terceros (softland),
//el navegador trabaja con cookie de sesion
class SessionController extends Controller
{
    //GET /login → muestra el form
    public function create(): View
    {
        return view('pages.auth.login');
    }

    //POST /login. LoginRequest ya valido q vengan email y password
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        //attempt busca por email y compara el hash. si falla tiro error de validacion
        //en el campo email (no digo cual de los 2 esta malo, por seguridad)
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        //regenerar el id de sesion al loguear evita session fixation
        $request->session()->regenerate();

        //intended = si venia de una url protegida lo devuelvo ahi, si no al dashboard
        return redirect()->intended(route('dashboard'));
    }

    //POST /logout
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
