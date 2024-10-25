<?php

namespace App\Interface\PlayerAnwer;

use App\DTOs\PlayerAnswerDTO;

interface PlayerAnswerRepositoryInterface
{
    public function createPlayerAnswer(PlayerAnswerDTO $playerAnswerDTO);
    public function  findAnswerByUserAndQuestion(int $userId, int $questionId);
}