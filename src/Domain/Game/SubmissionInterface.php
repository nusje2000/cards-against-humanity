<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\AnswerInterface;

interface SubmissionInterface
{
    public function getPlayer(): PlayerInterface;

    public function getAnswer(): AnswerInterface;
}
