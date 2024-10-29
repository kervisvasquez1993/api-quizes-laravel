<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerAnswer\StorePlayerAnswerRequest;
use App\Http\Resources\AnswerUserByQuestionResource;
use App\Http\Resources\PlayerAnswerResource;
use App\Services\PlayerAnwer\PlayerAnswerServices;
use Exception;


class PlayerAnswerController extends Controller
{

    protected PlayerAnswerServices $playerAnswerServices;
    public function __construct(PlayerAnswerServices $playerAnswerServices)
    {
        $this->playerAnswerServices = $playerAnswerServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // public function myAnswer()
    // {
    //     $myAnswer = auth()->user()->playerAnswer;
    //     return $myAnswer;
    // }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/questions/{id}/player-answer",
     *     tags={"Jogo"},
     *     summary="Registrar resposta do jogador para uma pergunta",
     *     description="Permite que um jogador registre sua resposta para uma pergunta específica. O usuário não pode responder a pergunta mais de uma vez.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pergunta para a qual o jogador está respondendo.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="given_answer", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resposta do jogador registrada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="question_id", type="integer", example=25),
     *             @OA\Property(property="given_answer", type="boolean", example=true),
     *             @OA\Property(property="is_correct", type="boolean", example=false),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T09:50:05.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T09:50:05.000000Z"),
     *             @OA\Property(property="id", type="integer", example=13)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao registrar resposta",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have already answered this question.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No results found for Question with ID 25")
     *         )
     *     )
     * )
     */
    public function store(StorePlayerAnswerRequest $request, $questionsId)
    {

        $result = $this->playerAnswerServices->playerAnswerByQuestion($request, $questionsId);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 201);
    }

    /**
     * @OA\Get(
     *     path="/api/my-answer-question",
     *     summary="Obter as respostas do usuário autenticado",
     *     description="Este endpoint permite que usuários autenticados obtenham uma lista de suas respostas às perguntas, incluindo se foram corretas ou incorretas.",
     *     operationId="getUserAnswers",
     *     tags={"Jogo"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respostas do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1, description="Identificador único da resposta"),
     *                     @OA\Property(property="user_id", type="integer", example=3, description="Identificador do usuário que respondeu à pergunta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=0, description="Indica se a resposta é correta (1) ou incorreta (0)"),
     *                     @OA\Property(property="question_name", type="string", example="pergunta falsa no projeto", description="Nome ou texto da pergunta"),
     *                     @OA\Property(property="question_image", type="string", nullable=true, example=null, description="URL da imagem associada à pergunta, se existir")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - O usuário não está autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */

    public function myAnswersQuestion()
    {
        $data = $this->playerAnswerServices->myAnswer();
        return PlayerAnswerResource::collection($data);
    }
    /**
     * @OA\Get(
     *     path="/api/user/{id}/answers",
     *     summary="Obter respostas de um usuário específico",
     *     description="Este endpoint permite obter uma lista de respostas de um usuário específico, identificando-o pelo seu ID na URL.",
     *     operationId="getUserAnswersById",
     *     tags={"Jogo"},
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário cujas respostas se deseja obter",
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respostas do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1, description="Identificador único da resposta"),
     *                     @OA\Property(property="user_id", type="integer", example=3, description="ID do usuário que respondeu à pergunta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=0, description="Indica se a resposta é correta (1) ou incorreta (0)"),
     *                     @OA\Property(property="question_name", type="string", example="Pergunta de exemplo", description="Nome ou texto da pergunta"),
     *                     @OA\Property(property="question_image", type="string", nullable=true, example=null, description="URL da imagem associada à pergunta, se existir")
     *                 )
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - O usuário não está autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuário não encontrado")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */

    public function getUserAnswers($id)
    {
        try {
            $data = $this->playerAnswerServices->getUserAnswersById($id);
            return PlayerAnswerResource::collection($data);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 404);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/questions/{id}/answers",
     *     summary="Obter respostas de usuários para uma pergunta específica",
     *     description="Este endpoint permite obter uma lista de respostas de usuários para uma pergunta específica, juntamente com informações sobre se a resposta foi correta e os dados do usuário.",
     *     operationId="getAnswersByQuestion",
     *     tags={"Jogo"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pergunta",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respostas de usuários para a pergunta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=3, description="ID da resposta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=1, description="Indica se a resposta é correta (1) ou incorreta (0)"),
     *                     @OA\Property(property="username", type="string", example="kervis1", description="Nome de usuário que respondeu"),
     *                     @OA\Property(property="email", type="string", example="kvfa131@gmail.com", description="Endereço de e-mail do usuário")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No query results for Question 5")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */

    public function getAnswersByQuestion($id)
    {
        $data = $this->playerAnswerServices->getQuestionAnswersById($id);
        if (!$data["success"]) {
            return response()->json([
                'message' => $data["message"]
            ], 404);
        }
        return AnswerUserByQuestionResource::collection($data['data']);;
    }

    /**
     * @OA\Get(
     *     path="/api/players-position",
     *     summary="Obtener puntos por usuarios",
     *     description="Este endpoint retorna una lista de todos los usuarios ordenados de mayor a menor según sus puntos.",
     *     operationId="getPlayersPosition",
     *     tags={"Jogo"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios ordenados por puntos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1, description="ID do usuário"),
     *                 @OA\Property(property="username", type="string", example="admin", description="Nome de usuário"),
     *                 @OA\Property(property="email", type="string", example="admin@example.com", description="Endereço de e-mail do usuário"),
     *                 @OA\Property(property="role", type="string", example="admin", description="Função do usuário"),
     *                 @OA\Property(property="points", type="integer", example=0, description="Pontuação do usuário"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-26T19:01:07.000000Z", description="Data de criação do usuário"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T07:45:27.000000Z", description="Data de atualização do usuário")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - O usuário não está autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */

    public function pointForUser()
    {
        $data = $this->playerAnswerServices->userOrdeByPoint();
        if (!$data["success"]) {
            return response()->json([
                'message' => $data["message"]
            ], 404);
        }
        return response()->json($data["data"], 200);
    }
}
