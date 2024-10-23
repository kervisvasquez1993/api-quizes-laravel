<?php

namespace App\Repositories\Auth;

use App\DTOs\LoginDTO;
use App\Interface\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthRepository  implements AuthRepositoryInterface
{
    public function login(LoginDTO $loginDTO)
    {
        if (!Auth::attempt($loginDTO->credentials())) {
            throw new \Exception('Invalid credentials');
        }

        return Auth::user();
    }
    public function register(array $data)
    {
        return User::create($data);
    }
}
