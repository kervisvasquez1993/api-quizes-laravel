<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuizDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\DeleteQuizRequest;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Services\Quiz\QuizServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuizController extends Controller
{
    protected QuizServices $quizServices;
    public function __construct(QuizServices $quizServices)
    {
        $this->quizServices = $quizServices;
    }

    /**
     * @OA\Get(
     *     path="/api/quiz",
     *  tags={"Quiz"},
     *     summary="Obter todos os quizzes",
     *     description="Retorna uma lista de todos os quizzes disponíveis.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de quizzes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=19),
     *                     @OA\Property(property="title", type="string", example="Quiz 2"),
     *                     @OA\Property(property="description", type="string", example="Queremos eliminar um quiz de forma correta")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $data = $this->quizServices->getAllQuizzes();
        return QuizResource::collection($data);
    }
    /**
     * @OA\Get(
     *     path="/api/quiz/{id}",
     *     tags={"Quiz"},
     *     summary="Obter um quiz específico",
     *     description="Retorna um quiz com base no ID fornecido.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do quiz",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=19),
     *                 @OA\Property(property="title", type="string", example="Quiz 2"),
     *                 @OA\Property(property="description", type="string", example="Queremos eliminar um quiz de forma correta")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No results found for Quiz with ID 190")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $data = $this->quizServices->getQuizById($id);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["message" => $data["message"]], Response::HTTP_NOT_FOUND);
        }
        return new QuizResource($data);
    }

    /**
     * @OA\Post(
     *     path="/api/quiz",
     *     tags={"Quiz"},
     *     summary="Criar um novo quiz",
     *     description="Cria um novo quiz com base nos dados fornecidos. Apenas usuários com o papel de administrador podem criar um novo registro.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Quiz 5"),
     *             @OA\Property(property="description", type="string", example="Esto es una descripcion 5")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quiz criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=20),
     *             @OA\Property(property="title", type="string", example="Quiz 5"),
     *             @OA\Property(property="description", type="string", example="Esto es uma descrição 5"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T02:19:50.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T02:19:50.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title has already been taken.")
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="array",
     *                     @OA\Items(type="string", example="The description has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreQuizRequest $request)
    {
        $result = $this->quizServices->createQuiz(QuizDTO::fromRequest($request));
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 201);
    }



    /**
     * @OA\Put(
     *     path="/api/quiz/{id}",
     *     tags={"Quiz"}, 
     *     summary="Atualizar um quiz existente",
     *     description="Atualiza um quiz existente com base nos dados fornecidos. Apenas usuários com o papel de administrador podem realizar esta ação.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="ada update"),
     *             @OA\Property(property="description", type="string", example="Esto es una actualizacion1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=20),
     *             @OA\Property(property="title", type="string", example="ada update"),
     *             @OA\Property(property="description", type="string", example="Esto es uma atualizacao1"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T02:19:50.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T09:19:34.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="messages", type="string", example="No results found for Quiz with ID 2122")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title has already been taken.")
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="array",
     *                     @OA\Items(type="string", example="The description has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateQuizRequest $request, string $id)
    {
        $data = $this->quizServices->updateQuiz(QuizDTO::fromUpdateRequest($request), $id);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["messages" => $data["message"]], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data['data'], status: 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/quiz/{id}",
     *     tags={"Quiz"}, 
     *     summary="Deletar um quiz existente",
     *     description="Remove um quiz existente. Apenas usuários com o papel de administrador podem realizar esta ação.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz removido com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=20),
     *             @OA\Property(property="title", type="string", example="ada 2"),
     *             @OA\Property(property="description", type="string", example="tes amo hija"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T21:02:46.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-28T21:02:46.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No results found for Quiz with ID 20")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $data = $this->quizServices->deletedQuiz($id);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["message" => $data['message']], 404);
        }
        return response()->json($data["data"], 201);
    }
}
