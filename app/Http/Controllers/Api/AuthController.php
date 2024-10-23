<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authServices;
    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authServices->login(LoginDTO::fromRequest($request));
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 401);
        }

        return response()->json($result['data']);
    }
    public function register(RegisterRequest $request)
    {

        // $result = $this->authServices
    }
}
