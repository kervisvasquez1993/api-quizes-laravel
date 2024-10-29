<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $authServices;
    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login do usuário",
     *     description="Autentica um usuário e retorna um token de acesso.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="kvfa13@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=3),
     *                 @OA\Property(property="username", type="string", example="kervis"),
     *                 @OA\Property(property="email", type="string", example="kvfa13@gmail.com"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:09:38.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T20:09:38.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Os dados fornecidos estão incorretos")
     *         )
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        $result = $this->authServices->login(LoginDTO::fromRequest($request));
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }
        return response()->json($result['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Registro de usuário",
     *     description="Registra um novo usuário e retorna os dados do usuário.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "password", "password_confirmation"},
     *             @OA\Property(property="username", type="string", example="kervis"),
     *             @OA\Property(property="email", type="string", format="email", example="kvfa13@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456789"),
     *             @OA\Property(property="password_confirmation", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="username", type="string", example="kervis1"),
     *                 @OA\Property(property="email", type="string", example="kvfa131@gmail.com"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T21:28:35.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T21:28:35.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=4)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erros de validação"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="username", type="array", @OA\Items(type="string", example="O campo username é obrigatório.")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="O campo email é obrigatório.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="O campo password é obrigatório."))
     *             )
     *         )
     *     )
     * )
     */

    public function register(RegisterRequest $request)
    {
        $result = $this->authServices->register(RegisterDTO::fromRequest($request));
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], 201);
    }
}
