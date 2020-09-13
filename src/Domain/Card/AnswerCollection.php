<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @phpstan-extends ObjectCollection<AnswerInterface>
 * @psalm-extends ObjectCollection<AnswerInterface>
 *
 * @method ArrayIterator|AnswerInterface[] getIterator()
 * @method AnswerInterface|null first()
 * @method AnswerInterface|null last()
 */
final class AnswerCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return AnswerInterface::class;
    }
}
