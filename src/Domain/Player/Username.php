<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Aeviiq\ValueObject\AbstractString;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class Username extends AbstractString
{
    public static function fromString(string $username): self
    {
        return new self($username);
    }

    /**
     * @inheritDoc
     */
    public static function getConstraints(): array
    {
        return [
            new NotBlank(),
            new Length([
                'min' => 4,
                'max' => 20,
            ]),
        ];
    }

    public function toString(): string
    {
        return $this->get();
    }
}
