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
    public function register(array $data)
    {
        return User::create($data);
    }
}
