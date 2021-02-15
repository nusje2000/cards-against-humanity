<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Exception\Player;

use LogicException;

final class NoPlayerFound extends LogicException
{
    public static function noToken(): self
    {
        return new self('No token found.');
    }

    /**
     * @param mixed $actual
     */
    public static function invalidUser(string $expectedInstance, $actual): self
    {
        return new self(
            sprintf('Invalid user found, expected %s. (found: "%s")', $expectedInstance, is_object($actual) ? get_class($actual) : gettype($actual))
        );
    }
}
