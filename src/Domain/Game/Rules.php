<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

final class Rules
{
    private int $handSize;

    private ?int $maxRounds;

    private function __construct(int $handSize, ?int $maxRounds)
    {
        $this->handSize = $handSize;
        $this->maxRounds = $maxRounds;
    }

    public static function default(): self
    {
        return new self(7, 8);
    }

    public static function custom(int $handSize, ?int $maxRounds): self
    {
        return new self($handSize, $maxRounds);
    }

    public function handSize(): int
    {
        return $this->handSize;
    }

    public function maxRounds(): ?int
    {
        return $this->maxRounds;
    }
}
