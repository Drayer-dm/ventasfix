<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

//mantenedor de usuarios WEB. mismos requests y mismo service q la api,
//lo unico distinto es q aca devuelvo vistas y redirects en vez de json
class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    //1.1 listar
    public function index(): View
    {
        return view('pages.users.index', ['users' => $this->userService->getAll()]);
    }

    //form de crear
    public function create(): View
    {
        return view('pages.users.create');
    }

    //1.3 agregar. si StoreUserRequest falla ni entra aca (vuelve al form con errores)
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    //1.2 ver uno. abort(404) si el id no existe → pagina 404
    public function show(int $id): View
    {
        $user = $this->userService->findById($id) ?? abort(404);

        return view('pages.users.show', compact('user'));
    }

    //form de editar
    public function edit(int $id): View
    {
        $user = $this->userService->findById($id) ?? abort(404);

        return view('pages.users.edit', compact('user'));
    }

    //1.4 actualizar
    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $user = $this->userService->findById($id) ?? abort(404);

        $this->userService->update($user, $request->validated());

        return redirect()->route('users.show', $id)->with('success', 'Usuario actualizado correctamente.');
    }

    //1.5 eliminar
    public function destroy(int $id): RedirectResponse
    {
        $user = $this->userService->findById($id) ?? abort(404);

        //no dejo q alguien se borre a si mismo estando logueado, quedaria una sesion zombie
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $this->userService->delete($user);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
