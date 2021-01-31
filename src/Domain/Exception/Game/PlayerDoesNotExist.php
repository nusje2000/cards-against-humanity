<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Game;

use Nusje2000\CAH\Domain\Player\Id;
use Throwable;
use UnexpectedValueException;

final class PlayerDoesNotExist extends UnexpectedValueException
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function withId(Id $id): self
    {
        return new self(sprintf('Could not find a player with id "%s".', $id->toString()));
    }
}
