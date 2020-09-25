<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Aeviiq\Collection\ObjectCollection;
use JsonSerializable;

/**
 * @extends ObjectCollection<int, SubmissionInterface>
 *
 * @method \Traversable<int, SubmissionInterface> getIterator()
 * @method SubmissionInterface|null first()
 * @method SubmissionInterface|null last()
 */
final class SubmissionCollection extends ObjectCollection implements JsonSerializable
{
    protected function allowedInstance(): string
    {
        return SubmissionInterface::class;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

