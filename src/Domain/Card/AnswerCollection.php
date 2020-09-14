<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\Collection\ObjectCollection;

/**
 * @extends ObjectCollection<int, AnswerInterface>
 *
 * @method \Traversable<int, AnswerInterface> getIterator()
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
