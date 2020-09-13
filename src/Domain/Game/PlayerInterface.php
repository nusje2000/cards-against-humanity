<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\AnswerCollection;

interface PlayerInterface
{
    public function getScore(): int;

    public function addScore(int $score): void;

    public function getHand(): AnswerCollection;
}
