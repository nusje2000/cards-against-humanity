<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Aeviiq\ValueObject\AbstractString;
use Symfony\Component\Validator\Constraints\Regex;

final class Username extends AbstractString
{
    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public function toString(): string
    {
        return $this->get();
    }

    public static function getConstraints(): array
    {
        return [
            new Regex([
                'pattern' => '/[\w_]{1,20}/',
            ]),
        ];
    }
}
