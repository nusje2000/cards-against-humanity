<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Round;

use JsonSerializable;
use Nusje2000\CAH\Domain\Exception\Game\NoActiveRound;
use Nusje2000\CAH\Domain\Exception\Round\UnfinishedRoundPresent;

final class Registry implements JsonSerializable
{
    /**
     * @var array<int, Round>
     */
    private array $completed = [];

    private ?Round $current = null;

    public function start(Round $round): void
    {
        if (null !== $this->current) {
            throw UnfinishedRoundPresent::create($this->current->id());
        }

        $this->current = $round;
    }

    public function finishCurrentRound(): void
    {
        $this->completed[] = $this->current();
        $this->current = null;
    }

    /**
     * @return array<int, Round>
     */
    public function completed(): array
    {
        return $this->completed;
    }

    public function hasActiveRound(): bool
    {
        return null !== $this->current;
    }

    public function current(): Round
    {
        if (null === $this->current) {
            throw NoActiveRound::create();
        }

        return $this->current;
    }

    public function previous(): ?Round
    {
        return end($this->completed) ?: null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'current' => $this->current,
            'completed' => $this->completed(),
            'previous' => $this->previous(),
        ];
    }
}
