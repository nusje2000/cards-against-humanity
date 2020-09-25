<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\Collection\ObjectCollection;
use JsonSerializable;

/**
 * @extends ObjectCollection<int, AnswerInterface>
 *
 * @method \Traversable<int, AnswerInterface> getIterator()
 * @method AnswerInterface|null first()
 * @method AnswerInterface|null last()
 */
final class AnswerCollection extends ObjectCollection implements JsonSerializable
{
    protected function allowedInstance(): string
    {
        return AnswerInterface::class;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
