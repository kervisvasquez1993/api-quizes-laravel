<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateUserPoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::with('playerAnswer')->get();

        foreach ($users as $user) {
            $correctAnswers = $user->playerAnswer->where('is_correct', 1)->count();

            $points = $correctAnswers * 10;

            $user->update(['points' => $points]);
        }
    }
}
