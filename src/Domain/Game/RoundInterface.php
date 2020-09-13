<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\QuestionInterface;

interface RoundInterface
{
    public function getCardCzar(): PlayerInterface;

    public function getQuestion(): QuestionInterface;

    public function getSubmissions(): SubmissionCollection;

    public function getWinner(): SubmissionInterface;

    public function isCompleted(): bool;
}
