<?php

namespace App\Services\Auth;

use App\DTOs\LoginDTO;
use App\Interface\Auth\AuthRepositoryInterface;

class AuthServices
{
    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->authRepository = $authRepositoryInterface;
    }

    public function login(LoginDTO $loginDTO)
    {
        try {
            $data = $this->authRepository->login($loginDTO);
            return [
                "success" => true,
                "data" => $data
            ];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), "e" => $ex];
        }
    }
}
