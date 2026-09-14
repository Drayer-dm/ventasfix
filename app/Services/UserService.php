<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

//capa de servicio de usuarios. aca va TODO lo q toca la bd de users, el controller
//no habla con el modelo directo: controller → service → model.
//asi si mañana cambia la logica (ej: mandar correo al crear) se toca aca y no
//en cada controller (la web y la api usan el mismo service)
class UserService
{
    //lista completa ordenada por id. si no hay nada devuelve collection vacia ([] en json)
    public function getAll(): Collection
    {
        return User::orderBy('id')->get();
    }

    //find devuelve null si no existe, el controller decide el 404 con eso
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    //$data ya viene validada del StoreUserRequest. la password llega en texto plano
    //y el cast 'hashed' del modelo la cifra antes del insert
    public function create(array $data): User
    {
        return User::create($data);
    }

    //update solo pisa los campos q vienen en $data (sometimes del request).
    //fresh() relee de la bd pa devolver el registro tal cual quedo
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
