<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @phpstan-extends ObjectCollection<QuestionInterface>
 * @psalm-extends ObjectCollection<QuestionInterface>
 *
 * @method ArrayIterator|QuestionInterface[] getIterator()
 * @method QuestionInterface|null first()
 * @method QuestionInterface|null last()
 */
final class QuestionCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return QuestionInterface::class;
    }
}
