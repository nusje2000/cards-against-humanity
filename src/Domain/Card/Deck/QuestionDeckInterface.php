<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card\Deck;

use Nusje2000\CAH\Domain\Card\AnswerInterface;

interface QuestionDeckInterface
{
    public function draw(): AnswerInterface;

    public function shuffle(): void;
}
