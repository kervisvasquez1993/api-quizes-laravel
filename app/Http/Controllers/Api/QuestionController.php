<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuestionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateImageQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\QuizResource;
use App\Services\Question\QuestionServices;
use App\Services\Quiz\QuizServices;
use Exception;


class QuestionController extends Controller
{
    protected QuizServices $quizServices;
    protected QuestionServices $questionServices;

    public function __construct(QuizServices $quizServices, QuestionServices $questionServices)
    {
        $this->quizServices = $quizServices;
        $this->questionServices = $questionServices;
    }

    public function index()
    {
        $data = $this->quizServices->getAllQuizzes();
        // return QuizResource::collection($data);
    }

    public function questionForQuiz($quizId)
    {
        try {
            $data = $this->quizServices->questionForQuiz($quizId);
            return new QuizResource($data);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }
    }
    /**
     * @OA\Post(
     *     path="/quiz/{quizId}/questions",
     *     tags={"Questions"},
     *     summary="Create a question for a quiz",
     *     description="Requires authentication and validates that quizId exists.",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the quiz to which the question will be added"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="question", type="string", example="ejemplo de pregunta ?"),
     *             @OA\Property(property="correct_answer", type="boolean", example=true),
     *             @OA\Property(property="image", type="string", format="binary", nullable=true, example="/storage/quizzes/kfVLLQMIeBv7dnhxL3hsnfFjw6WUwoErmu575iiv.png")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Question created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="question", type="string", example="hola ?"),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="image", type="string", example="/storage/quizzes/kfVLLQMIeBv7dnhxL3hsnfFjw6WUwoErmu575iiv.png"),
     *             @OA\Property(property="correct_answer", type="boolean", example=false),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-25T20:19:05.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-25T20:19:05.000000Z"),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model Quiz 10")
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
     *     )
     * )
     */
    public function store(StoreQuestionRequest $request, $quizId)
    {

        try {
            $quiz = $this->quizServices->findQuizOrFail($quizId);
            $imgFile = $this->questionServices->saveFile($request->image);
            $result = $this->questionServices->createQuestion(QuestionDTO::fromRequest($request, $quiz->id, $imgFile));
            if (!$result['success']) {
                return response()->json([
                    'error' => $result['message']
                ], 422);
            }
            return response()->json($result['data'], status: 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }
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
                'error' => $result['message']
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
        $question = $this->questionServices->findQuestionOrFail($id);
        $result = $this->questionServices->updateImage($request->image, $question->id);
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 200);
    }
}
