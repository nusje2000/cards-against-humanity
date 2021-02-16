<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Game;

use LogicException;
use Nusje2000\CAH\Domain\Player\Id;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class PlayerHasJoinedAlready extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function create(Id $id, ?Throwable $previous = null): self
    {
        return new self(sprintf('Player "%s" could not join, already in game.', $id->toString()), $previous);
    }
}
