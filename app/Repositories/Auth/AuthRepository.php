<?php

namespace App\Repositories\Auth;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Interface\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository  implements AuthRepositoryInterface
{
    public function login(LoginDTO $loginDTO)
    {
        if (!Auth::attempt($loginDTO->credentials())) {
            return [
                'success' => false
            ];
        }
        return [
            'success' => true,
            'user' => Auth::user()
        ];
    }
    public function createAccessToken(User $user): array
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken
        ];
    }
    public function createUser(RegisterDTO $registerDTO): User
    {
        return User::create([
            'username' => $registerDTO->getUsername(),
            'email' => $registerDTO->getEmail(),
            'password' => Hash::make($registerDTO->getPassword()),
            'role' => $registerDTO->getRole()
        ]);
    }
}
