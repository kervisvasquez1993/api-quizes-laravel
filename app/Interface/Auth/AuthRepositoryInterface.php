<?php

namespace App\Interface\Auth;

use App\DTOs\LoginDTO;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function login(LoginDTO $loginDTO);
    public function createAccessToken(User $user);
    public function register(array $data);
}
