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

    public function index()
    {
        $data = $this->quizServices->getAllQuizzes();
        return QuizResource::collection($data);
    }

    public function show($id)
    {
        $data = $this->quizServices->getQuizById($id);
        if (isset($data['success']) && !$data['success']) {
            return response()->json(["messages" => $data["message"]], Response::HTTP_NOT_FOUND);
        }
        return new QuizResource($data);
    }

    /**
     * @OA\Post(
     *     path="/quiz",
     *     tags={"Quiz"},
     *     summary="Create a new quiz",
     *     description="Requires authentication and admin role to create a quiz.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="quiz example"),
     *             @OA\Property(property="description", type="string", example="Descripcion de ejemplo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quiz created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="quiz", type="object",
     *                 @OA\Property(property="title", type="string", example="Cristianismo"),
     *                 @OA\Property(property="description", type="string", example="Esto es una desripcion de amor"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:05:28.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T20:05:28.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="The title field is required."))
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
     *         description="Not authorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to perform this action.")
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
     *     path="/quiz/{id}",
     *     tags={"Quiz"},
     *     summary="Update an existing quiz",
     *     description="Requires authentication and admin role to update a quiz.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the quiz to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="update title"),
     *             @OA\Property(property="description", type="string", example="Esto es una description de comida111")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="quiz", type="object",
     *                 @OA\Property(property="title", type="string", example="update title"),
     *                 @OA\Property(property="description", type="string", example="Esto es una description de comida111"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:05:28.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T20:05:28.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No query results for model Quiz 20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="The title field is required."))
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
     *         description="Not authorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to perform this action.")
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
     *     path="/quiz/{id}",
     *     tags={"Quiz"},
     *     summary="Delete a quiz",
     *     description="Requires authentication and admin role to delete a quiz.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the quiz to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Record deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No query results for model Quiz 20")
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
     *         description="Not authorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to perform this action.")
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
        return response()->json( $data["data"], 201);
    }
}
