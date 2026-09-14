<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

//mantenedor de clientes empresa WEB
class ClientController extends Controller
{
    public function __construct(private readonly ClientService $clientService)
    {
    }

    //3.1 listar
    public function index(): View
    {
        return view('pages.clients.index', ['clients' => $this->clientService->getAll()]);
    }

    public function create(): View
    {
        return view('pages.clients.create');
    }

    //3.3 agregar
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->clientService->create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
    }

    //3.2 ver uno
    public function show(int $id): View
    {
        $client = $this->clientService->findById($id) ?? abort(404);

        return view('pages.clients.show', compact('client'));
    }

    public function edit(int $id): View
    {
        $client = $this->clientService->findById($id) ?? abort(404);

        return view('pages.clients.edit', compact('client'));
    }

    //3.4 actualizar
    public function update(UpdateClientRequest $request, int $id): RedirectResponse
    {
        $client = $this->clientService->findById($id) ?? abort(404);

        $this->clientService->update($client, $request->validated());

        return redirect()->route('clients.show', $id)->with('success', 'Cliente actualizado correctamente.');
    }

    //3.5 eliminar
    public function destroy(int $id): RedirectResponse
    {
        $client = $this->clientService->findById($id) ?? abort(404);

        $this->clientService->delete($client);

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
