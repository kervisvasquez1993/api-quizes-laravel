<?php

namespace App\Services\Auth;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Interface\Auth\AuthRepositoryInterface;
use Exception;

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
            $authResult = $this->authRepository->login($loginDTO);

            if (!$authResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Los datos suministrados son incorrectos'
                ];
            }

            $user = $authResult['user'];
            $tokenResult = $this->authRepository->createAccessToken($user);

            return [
                'success' => true,
                'data' => [
                    'access_token' => $tokenResult['access_token'],
                    'data' => $user
                ]
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage()
            ];
        }
    }

    public function register(RegisterDTO $registerDTO)
    {
        try {
            $user = $this->authRepository->createUser($registerDTO);


            return [
                'success' => true,
                'data' => [
                    'user' => $user
                ]
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage()
            ];
        }
    }
}
