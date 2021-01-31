<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Aeviiq\ValueObject\AbstractString;

final class Text extends AbstractString
{
    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public static function getConstraints(): array
    {
        return [];
    }

    public function toString(): string
    {
        return $this->get();
    }
}
