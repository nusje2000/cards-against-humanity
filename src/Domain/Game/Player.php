<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use JsonSerializable;
use Nusje2000\CAH\Domain\Card\AnswerCollection;

final class Player implements PlayerInterface, JsonSerializable
{
    private string $name;
    private int $score = 0;
    private AnswerCollection $hand;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new AnswerCollection();
    }

    public function getName(): string
    {
        return $this->name;
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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'score' => $this->getScore(),
            'hand' => $this->getHand(),
        ];
    }
}
