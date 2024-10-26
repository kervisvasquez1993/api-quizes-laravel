<?php

namespace App\Services\Auth;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Interface\Auth\AuthRepositoryInterface;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
                    'message' => 'The provided data is incorrect'
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
                'data' =>  $user,
                'error' => "test"
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'code' => "442"
            ];
        }
    }

    public function validateRole()
    {
        if (!Gate::allows('validate-role', Auth::user())) {
            throw new \Exception("You do not have permission to perform this action.");
        }
    }
}
