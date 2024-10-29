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
     *     summary="Obtener las respuestas del usuario autenticado",
     *     description="Este endpoint permite a los usuarios autenticados obtener una lista de sus respuestas a las preguntas, incluyendo si fueron correctas o incorrectas.",
     *     operationId="getUserAnswers",
     *     tags={"Respuestas"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respuestas del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1, description="Identificador único de la respuesta"),
     *                     @OA\Property(property="user_id", type="integer", example=3, description="Identificador del usuario que respondió la pregunta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=0, description="Indica si la respuesta es correcta (1) o incorrecta (0)"),
     *                     @OA\Property(property="question_name", type="string", example="pregunta falsa en el proyecto", description="Nombre o texto de la pregunta"),
     *                     @OA\Property(property="question_image", type="string", nullable=true, example=null, description="URL de la imagen asociada a la pregunta, si existe")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - El usuario no está autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
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
     *     summary="Obtener respuestas de un usuario específico",
     *     description="Este endpoint permite obtener una lista de respuestas de un usuario en específico, identificándolo mediante su ID en la URL.",
     *     operationId="getUserAnswersById",
     *     tags={"Respuestas"},
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario cuyas respuestas se desean obtener",
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respuestas del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1, description="Identificador único de la respuesta"),
     *                     @OA\Property(property="user_id", type="integer", example=3, description="ID del usuario que respondió la pregunta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=0, description="Indica si la respuesta es correcta (1) o incorrecta (0)"),
     *                     @OA\Property(property="question_name", type="string", example="Pregunta de ejemplo", description="Nombre o texto de la pregunta"),
     *                     @OA\Property(property="question_image", type="string", nullable=true, example=null, description="URL de la imagen asociada a la pregunta, si existe")
     *                 )
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - El usuario no está autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
     *     summary="Obtener respuestas de usuarios a una pregunta específica",
     *     description="Este endpoint permite obtener una lista de respuestas de usuarios a una pregunta específica, junto con la información de si fue correcta y los datos del usuario.",
     *     operationId="getAnswersByQuestion",
     *     tags={"Respuestas"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la pregunta",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de respuestas de usuarios para la pregunta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=3, description="ID de la respuesta"),
     *                     @OA\Property(property="is_correct", type="boolean", example=1, description="Indica si la respuesta es correcta (1) o incorrecta (0)"),
     *                     @OA\Property(property="username", type="string", example="kervis1", description="Nombre de usuario que respondió"),
     *                     @OA\Property(property="email", type="string", example="kvfa131@gmail.com", description="Correo electrónico del usuario")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Pregunta no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No query results for Question 5")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
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

    public function pointForUser(){
        $data = $this->playerAnswerServices->userOrdeByPoint();
        if (!$data["success"]) {
            return response()->json([
                'message' => $data["message"]
            ], 404);
        }
        return response()->json($data["data"], 200);
    }
}
