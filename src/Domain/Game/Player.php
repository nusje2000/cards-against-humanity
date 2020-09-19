<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\AnswerCollection;

final class Player implements PlayerInterface
{
    private int $score = 0;
    private AnswerCollection $hand;

    public function __construct()
    {
        $this->hand = new AnswerCollection();
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function addScore(int $score): void
    {
        $this->score += $score;
    }

    public function getHand(): AnswerCollection
    {
        return $this->hand;
    }
}
