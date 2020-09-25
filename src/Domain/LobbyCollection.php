<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain;

use Aeviiq\Collection\ObjectCollection;
use JsonSerializable;

/**
 * @extends ObjectCollection<int, LobbyInterface>
 *
 * @method \Traversable<int, LobbyInterface> getIterator()
 * @method LobbyInterface|null first()
 * @method LobbyInterface|null last()
 */
final class LobbyCollection extends ObjectCollection implements JsonSerializable
{
    protected function allowedInstance(): string
    {
        return LobbyInterface::class;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
