<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

//datos de partida pa poder entrar y mostrar el sistema. se corre con:
//  php artisan migrate:fresh --seed
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //usuarios pa loguearse (la clave la hashea el modelo).
        //no hay roles: todo usuario autenticado administra todo (web y api)
        User::create([
            'rut'        => '19.876.543-2',
            'first_name' => 'Drayer',
            'last_name'  => 'Durán',
            'email'      => 'admin@ventasfix.cl',
            'password'   => 'Admin1234',
        ]);

        //usuario de prueba pa demos y pa probar la api en swagger/postman
        User::create([
            'rut'        => '11.111.111-1',
            'first_name' => 'Usuario',
            'last_name'  => 'Test',
            'email'      => 'test@ventasfix.cl',
            'password'   => 'Test1234',
        ]);

        //3 productos con stocks distintos pa q se vean los 3 badges (critico/bajo/normal/alto).
        //la imagen apunta al logo pq es la unica q hay en public/, despues se suben las reales
        $products = [
            ['sku' => 'TEC-001', 'name' => 'Teclado mecánico TKL', 'short_description' => 'Switches red, retroiluminado', 'long_description' => 'Teclado mecánico sin pad numérico, switches lineales, cable USB-C desmontable.', 'net_price' => 45000, 'current_stock' => 25, 'minimum_stock' => 5, 'low_stock' => 10, 'high_stock' => 100],
            ['sku' => 'MOU-014', 'name' => 'Mouse inalámbrico', 'short_description' => '2.4 GHz + Bluetooth', 'long_description' => 'Mouse ergonómico con sensor óptico de 4000 DPI y batería recargable.', 'net_price' => 18990, 'current_stock' => 4, 'minimum_stock' => 5, 'low_stock' => 10, 'high_stock' => 80],
            ['sku' => 'MON-027', 'name' => 'Monitor 27" IPS', 'short_description' => 'QHD 165 Hz', 'long_description' => 'Panel IPS de 27 pulgadas, resolución 2560x1440, 165 Hz, HDMI 2.1 y DisplayPort.', 'net_price' => 219000, 'current_stock' => 150, 'minimum_stock' => 3, 'low_stock' => 8, 'high_stock' => 60],
        ];

        foreach ($products as $product) {
            Product::create($product + ['image' => 'images/logo.png']);
        }

        Client::create([
            'company_rut'     => '76.123.456-7',
            'business_sector' => 'Retail',
            'business_name'   => 'Comercial Sur SpA',
            'phone'           => '+56 9 1234 5678',
            'address'         => 'Av. Libertador 1234, Santiago',
            'contact_name'    => 'Luis Soto',
            'contact_email'   => 'luis.soto@comercialsur.cl',
        ]);

        Client::create([
            'company_rut'     => '77.987.654-3',
            'business_sector' => 'Construcción',
            'business_name'   => 'Constructora Andes Ltda.',
            'phone'           => '+56 2 2345 6789',
            'address'         => 'Camino El Alba 456, Las Condes',
            'contact_name'    => 'María Rojas',
            'contact_email'   => 'mrojas@andes.cl',
        ]);
    }
}
