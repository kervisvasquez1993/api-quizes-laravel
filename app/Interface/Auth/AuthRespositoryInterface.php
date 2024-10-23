<?php

namespace App\Interface\Auth;

interface AuthRespositoryInterface
{
    public function login(array $data);
    public function register(array $data);
}
