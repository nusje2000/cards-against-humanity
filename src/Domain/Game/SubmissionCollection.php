<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @phpstan-extends ObjectCollection<SubmissionInterface>
 * @psalm-extends ObjectCollection<SubmissionInterface>
 *
 * @method ArrayIterator|SubmissionInterface[] getIterator()
 * @method SubmissionInterface|null first()
 * @method SubmissionInterface|null last()
 */
final class SubmissionCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return SubmissionInterface::class;
    }
}

