<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Quiz\QuizServices;
use App\Services\Auth\AuthServices;
use App\DTOs\QuizDTO;
use App\Models\Quiz;
use App\Interface\Quiz\QuizRepositoryInterface;


use Mockery;
use Exception;
class QuizServiceTest extends TestCase
{
    protected QuizServices $quizService;
    protected $quizRepositoryMock;
    protected $authServicesMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->quizRepositoryMock = Mockery::mock(QuizRepositoryInterface::class);
        $this->authServicesMock = Mockery::mock(AuthServices::class);

        $this->quizService = new QuizServices(
            $this->quizRepositoryMock,
            $this->authServicesMock
        );
    }

    public function testGetAllQuizzes()
    {
        $quizzes = [
            ['id' => 1, 'title' => 'Quiz 1', 'description' => 'Description 1', 'user_id' => 1],
            ['id' => 2, 'title' => 'Quiz 2', 'description' => 'Description 2', 'user_id' => 2]
        ];

        $this->quizRepositoryMock
            ->shouldReceive('getAllQuiz')
            ->once()
            ->andReturn($quizzes);

        $result = $this->quizService->getAllQuizzes();

        $this->assertEquals($quizzes, $result);
    }

    public function testUpdateQuizSuccess()
    {
        $quizId = 1;
        $quizMock = Mockery::mock(Quiz::class);
    
        $quizDTO = new QuizDTO(
            'Updated Title',
            'Updated Description',
            1
        );
    
        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->with($quizId)
            ->andReturn($quizMock);
    
        $this->quizRepositoryMock
            ->shouldReceive('updateQuiz')
            ->with($quizMock, $quizDTO)
            ->andReturn($quizMock);
    
        $result = $this->quizService->updateQuiz($quizDTO, $quizId);
    
        $this->assertTrue($result['success']);
        $this->assertEquals($quizMock, $result['data']);
    }
    public function testQuestionForQuizFailure()
    {
        $quizId = 1;

        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->once()
            ->with($quizId)
            ->andThrow(new Exception('Quiz not found'));

        $result = $this->quizService->questionForQuiz($quizId);

        $this->assertFalse($result['success']);
        $this->assertEquals('Quiz not found', $result['message']);
    }

    public function testGetQuizByIdSuccess()
    {
        $quizId = 1;
        $quiz = ['id' => $quizId, 'title' => 'Sample Quiz'];

        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->once()
            ->with($quizId)
            ->andReturn($quiz);

        $result = $this->quizService->getQuizById($quizId);

        $this->assertEquals($quiz, $result);
    }

    public function testGetQuizByIdFailure()
    {
        $quizId = 1;

        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->once()
            ->with($quizId)
            ->andThrow(new Exception('Quiz not found'));

        $result = $this->quizService->getQuizById($quizId);

        $this->assertFalse($result['success']);
        $this->assertEquals('Quiz not found', $result['message']);
    }

  
    public function testUpdateQuizFailure()
    {
        $quizId = 1;
        $quizDTO = new QuizDTO(
            title: 'Updated Quiz',
            description: 'Updated Description',
            userId: 1
        );

        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->once()
            ->with($quizId)
            ->andThrow(new Exception('Quiz not found'));

        $result = $this->quizService->updateQuiz($quizDTO, $quizId);

        $this->assertFalse($result['success']);
        $this->assertEquals('Quiz not found', $result['message']);
    }

    public function testDeletedQuizSuccess()
    {
        $quizId = 1;
        $quiz = ['id' => $quizId, 'title' => 'Sample Quiz'];

        $this->authServicesMock
            ->shouldReceive('validateRole')
            ->once();

        $this->quizRepositoryMock
            ->shouldReceive('getQuizById')
            ->once()
            ->with($quizId)
            ->andReturn($quiz);

        $this->quizRepositoryMock
            ->shouldReceive('deletedQuiz')
            ->once()
            ->with($quizId);

        $result = $this->quizService->deletedQuiz($quizId);

        $this->assertTrue($result['success']);
        $this->assertEquals($quiz, $result['data']);
    }

    public function testDeletedQuizFailure()
    {
        $quizId = 1;

        $this->authServicesMock
            ->shouldReceive('validateRole')
            ->once()
            ->andThrow(new Exception('Unauthorized'));

        $result = $this->quizService->deletedQuiz($quizId);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized', $result['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}