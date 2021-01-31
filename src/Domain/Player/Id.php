<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Aeviiq\ValueObject\AbstractString;
use Ramsey\Uuid\UuidInterface;

final class Id extends AbstractString
{
    private function __construct(string $string)
    {
        parent::__construct($string);
    }

    public static function fromString(string $string): self
    {
        return new self($string);
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
