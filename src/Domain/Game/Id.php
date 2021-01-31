<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Aeviiq\ValueObject\AbstractString;
use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\UuidInterface;

final class Id extends AbstractString implements AggregateRootId
{
    private function __construct(string $string)
    {
        parent::__construct($string);
    }

    public static function fromString(string $aggregateRootId): self
    {
        return new self($aggregateRootId);
    }

    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid->toString());
    }

    public function toString(): string
    {
        return $this->get();
    }

    public static function getConstraints(): array
    {
        return [];
    }
}
