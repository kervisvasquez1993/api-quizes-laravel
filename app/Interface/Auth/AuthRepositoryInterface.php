<?php

namespace App\Interface\Auth;

use App\DTOs\LoginDTO;

interface AuthRepositoryInterface
{
    public function login(LoginDTO $loginDTO);
    public function register(array $data);
}
