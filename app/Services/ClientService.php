<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

//capa de servicio de clientes empresa: controller → service → model
class ClientService
{
    public function getAll(): Collection
    {
        return Client::orderBy('id')->get();
    }

    //null si no existe → el controller responde 404
    public function findById(int $id): ?Client
    {
        return Client::find($id);
    }

    //$data validada por StoreClientRequest
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client->fresh();
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}
