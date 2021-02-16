<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Game;

use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Username;
use Throwable;
use UnexpectedValueException;

/**
 * @codeCoverageIgnore
 */
final class PlayerDoesNotExist extends UnexpectedValueException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function withId(PlayerId $id): self
    {
        return new self(sprintf('Could not find a player with id "%s".', $id->toString()));
    }

    public static function withUsername(Username $username): self
    {
        return new self(sprintf('Could not find a player with username "%s".', $username->get()));
    }
}
