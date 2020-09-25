<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Aeviiq\Collection\ObjectCollection;
use JsonSerializable;

/**
 * @extends ObjectCollection<int, PlayerInterface>
 *
 * @method \Traversable<int, PlayerInterface> getIterator()
 * @method PlayerInterface|null first()
 * @method PlayerInterface|null last()
 */
final class PlayerCollection extends ObjectCollection implements JsonSerializable
{
    protected function allowedInstance(): string
    {
        return PlayerInterface::class;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
