<?php

namespace App\Jobs;

use App\Models\PlayerAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePlayerAnswersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $question;

    /**
     * Crea una nueva instancia del job.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Ejecuta el job.
     *
     * @return void
     */
    public function handle()
    {
        $newCorrectAnswer = $this->question->correct_answer;
        $playerAnswers = PlayerAnswer::where('question_id', $this->question->id)->get();

        foreach ($playerAnswers as $answer) {
            $user = $answer->user; 
            if ($answer->is_correct && $answer->given_answer != $newCorrectAnswer) {
                $answer->is_correct = false;
                $user->points = max(0, $user->points - 10);
                $user->save();
            }
            elseif (!$answer->is_correct && $answer->given_answer == $newCorrectAnswer) {

                $answer->is_correct = true;
                $user->points += 10;
                $user->save();
            }
            $answer->save();
        }
    }
}
