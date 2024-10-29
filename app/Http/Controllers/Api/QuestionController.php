<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuestionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateImageQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Models\Question;
use App\Services\Question\QuestionServices;
use App\Services\Quiz\QuizServices;
use Exception;
use Illuminate\Http\Response;


class QuestionController extends Controller
{
    protected QuizServices $quizServices;
    protected QuestionServices $questionServices;

    public function __construct(QuizServices $quizServices, QuestionServices $questionServices)
    {
        $this->quizServices = $quizServices;
        $this->questionServices = $questionServices;
    }



    /**
     * @OA\Get(
     *     path="/api/quiz/{quizId}/questions",
     *     tags={"Questions"},
     *     summary="Listar perguntas por quiz",
     *     description="Retorna uma lista de perguntas associadas a um quiz específico.",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID do quiz para o qual as perguntas devem ser listadas",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de perguntas do quiz",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=19),
     *                 @OA\Property(property="title", type="string", example="Quiz 2"),
     *                 @OA\Property(property="description", type="string", example="Queremos eliminar um quiz de forma correta"),
     *                 @OA\Property(property="questions", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=25),
     *                         @OA\Property(property="question", type="string", example="hola mundo"),
     *                         @OA\Property(property="image", type="string", nullable=true, example=null),
     *                         @OA\Property(property="correct_answer", type="integer", example=0),
     *                         @OA\Property(property="answer_count", type="integer", example=1),
     *                         @OA\Property(property="quiz_name", type="string", example="Quiz 2"),
     *                         @OA\Property(property="quiz_id", type="integer", example=19)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No results found for Quiz with ID 18")
     *         )
     *     )
     * )
     */
    public function questionForQuiz($quizId)
    {

        $data = $this->quizServices->questionForQuiz($quizId);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["message" => $data["message"]], Response::HTTP_NOT_FOUND);
        }
        return new QuizResource($data);
    }
    /**
     * @OA\Post(
     *     path="/api/quiz/{quiz_id}/questions",
     *     tags={"Questions"},
     *     summary="Criar uma pergunta para um quiz",
     *     description="Permite que apenas administradores criem uma nova pergunta associada a um quiz específico. Esta ação é restrita a usuários com o papel de administrador.",
     *     @OA\Parameter(
     *         name="quiz_id",
     *         in="path",
     *         required=true,
     *         description="ID do quiz ao qual a pergunta será associada.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="question", type="string", example="Isso é uma pergunta séria"),
     *             @OA\Property(property="correct_answer", type="boolean", example=false),
     *             @OA\Property(property="image", type="string", example="localsdsdsd")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pergunta criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="question", type="string", example="isto é uma atualização sim ou não"),
     *             @OA\Property(property="quiz_id", type="integer", example=18),
     *             @OA\Property(property="image", type="string", example="/storage/quizzes/FAhl3LgBmx9dGOENz0ufFL9wbijOttTTLMMOm7zS.png"),
     *             @OA\Property(property="correct_answer", type="boolean", example=true),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T02:32:33.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T02:32:33.000000Z"),
     *             @OA\Property(property="id", type="integer", example=18)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No results found for Quiz with ID 18")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="question", type="array", @OA\Items(type="string", example="O campo pergunta é obrigatório.")),
     *                 @OA\Property(property="correct_answer", type="array", @OA\Items(type="string", example="O campo resposta correta é obrigatório.")),
     *                 @OA\Property(property="image", type="array", @OA\Items(type="string", example="A imagem deve ser uma URL válida."))
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreQuestionRequest $request, $quizId)
    {
        $data = $this->questionServices->createQuestionWithQuiz($request, $quizId);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["message" => $data["message"]], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data["data"], 201);
    }
    /**
     * @OA\Put(
     *     path="/questions/{id}",
     *     tags={"Questions"},
     *     summary="Update a question",
     *     description="Requires authentication, admin role, and validates that the question exists.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the question to be updated"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="question", type="string", example="aupdate pregunta"),
     *             @OA\Property(property="correct_answer", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Question updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="question", type="string", example="aupdate pregunta"),
     *             @OA\Property(property="image", type="string", example="/storage/quizzes/kfVLLQMIeBv7dnhxL3hsnfFjw6WUwoErmu575iiv.png"),
     *             @OA\Property(property="correct_answer", type="boolean", example=true),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:19:05.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T21:59:01.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Question not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No query results for model Question 10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="question", type="array", @OA\Items(type="string", example="The question has already been taken."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to perform this action.")
     *         )
     *     )
     * )
     */
    public function update(UpdateQuestionRequest $request, $id)
    {
        $result = $this->questionServices->updateQuestion(QuestionDTO::fromUpdateRequest($request), $id);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 200);
    }
    /**
     * @OA\Post(
     *     path="/questions/{id}/image",
     *     tags={"Questions"},
     *     summary="Update the image of a question",
     *     description="Requires authentication, admin role, and validates that the question exists.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the question to update the image"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="image", type="string", format="binary", description="Image file to upload")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="question", type="string", example="aupdate pregunta"),
     *             @OA\Property(property="image", type="string", example="/storage/questions/lSbmAxXPRhkrL2LYo2J0RiY05CMstDzpK4bMIpzP.png"),
     *             @OA\Property(property="correct_answer", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:19:05.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T22:03:38.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="image", type="array", @OA\Items(type="string", example="The image field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Question not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No query results for model Question 10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to perform this action.")
     *         )
     *     )
     * )
     */

    public function updateImage(UpdateImageQuestionRequest $request, $id)
    {
        try {
            $question = $this->questionServices->findQuestionOrFail($id);
            $result = $this->questionServices->updateImage($request->image, $question->id);
            if (!$result['success']) {
                return response()->json([
                    'message' => $result['message']
                ], 422);
            }
            return response()->json($result['data'], status: 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 404);
        }
    }
    public function show($id)
    {
        $data = $this->questionServices->show($id);
        if (!$data['success']) {
            return response()->json([
                'message' => $data['message']
            ], 404);
        }
        return response()->json($data["data"], 200);
    }
    /**
     * @OA\Delete(
     *     path="/api/questions/{id}",
     *     tags={"Questions"},
     *     summary="Deletar uma pergunta",
     *     description="Permite que apenas administradores deletam uma pergunta específica. Esta ação é restrita a usuários com o papel de administrador.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pergunta que será deletada.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pergunta deletada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=19),
     *             @OA\Property(property="quiz_id", type="integer", example=18),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="question", type="string", example="amas a Dios ?"),
     *             @OA\Property(property="image", type="string", nullable=true, example=null),
     *             @OA\Property(property="correct_answer", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T03:42:21.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T03:42:21.000000Z"),
     *             @OA\Property(property="quiz", type="object",
     *                 @OA\Property(property="id", type="integer", example=18),
     *                 @OA\Property(property="title", type="string", example="Quiz 1"),
     *                 @OA\Property(property="description", type="string", example="Esto es un ejemplo de quiz"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T01:58:21.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T01:58:21.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No results found for Question with ID 19")
     *         )
     *     )
     * )
     */


    public function destroy($id)
    {
        $data = $this->questionServices->deleteQuestion($id);
        if (!$data['success']) {
            return response()->json([
                'message' => $data['message']
            ], 404);
        }
        return response()->json($data["data"], 201);
    }
    /**
     * @OA\Get(
     *     path="/api/questions",
     *     tags={"Questions"}, 
     *     summary="Obter todas as perguntas",
     *     description="Retorna uma lista de todas as perguntas disponíveis.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de perguntas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=25),
     *                     @OA\Property(property="question", type="string", example="hola mundo"),
     *                     @OA\Property(property="image", type="string", nullable=true, example=null),
     *                     @OA\Property(property="correct_answer", type="integer", example=0),
     *                     @OA\Property(property="answer_count", type="integer", example=1),
     *                     @OA\Property(property="quiz_name", type="string", example="Quiz 2"),
     *                     @OA\Property(property="quiz_id", type="integer", example=19)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        // TODO: AJUSTAR ESTO PARA ENVIARLO A LA LOGIA QUE CORRESPONDE 
        $data = Question::with('quiz')->get();
        return QuestionResource::collection($data);
    }
}
