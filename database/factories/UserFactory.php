<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

//factory de usuarios (pa tests o pa generar muchos de prueba). el q trae laravel usaba
//'name' y ya no existe, aca van los campos reales de la tabla users
/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rut'        => $this->faker->unique()->numerify('1#.###.###') . '-' . $this->faker->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'K']),
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->unique()->userName() . '@ventasfix.cl',
            'password'   => 'password', //el modelo la hashea
        ];
    }
}
