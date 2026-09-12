<?php

namespace App\Models;

//test use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

#[Fillable(['rut', 'first_name', 'last_name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected function casts(): array //Oh no mi contrasenia (ola no tengo la enie)
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier(): mixed //Valor que va en el claim del sub token
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims():array //claims para el payload del token
    {
        return [
            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users',
                ],

                'api' => [
                    'driver' => 'jwt',
                    'provider' => 'users',
                ],
            ],
        ];
    }
}
