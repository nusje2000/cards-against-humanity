<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @phpstan-extends ObjectCollection<RoundInterface>
 * @psalm-extends ObjectCollection<RoundInterface>
 *
 * @method ArrayIterator|RoundInterface[] getIterator()
 * @method RoundInterface|null first()
 * @method RoundInterface|null last()
 */
final class RoundCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return RoundInterface::class;
    }
}
