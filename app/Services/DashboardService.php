<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;

//servicio del dashboard (requerimiento 4): cuantos usuarios, productos y clientes hay.
//count() hace un SELECT COUNT(*) directo, no trae los registros, asi q es liviano
class DashboardService
{
    public function getSummary(): array
    {
        return [
            'users'    => User::count(),
            'products' => Product::count(),
            'clients'  => Client::count(),
        ];
    }
}
