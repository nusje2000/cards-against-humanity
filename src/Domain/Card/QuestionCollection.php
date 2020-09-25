<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\Collection\ObjectCollection;
use JsonSerializable;

/**
 * @extends ObjectCollection<int, QuestionInterface>
 *
 * @method \Traversable<int, QuestionInterface> getIterator()
 * @method QuestionInterface|null first()
 * @method QuestionInterface|null last()
 */
final class QuestionCollection extends ObjectCollection implements JsonSerializable
{
    protected function allowedInstance(): string
    {
        return QuestionInterface::class;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
