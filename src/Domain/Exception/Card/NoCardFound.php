<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Card;

use LogicException;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Throwable;

final class NoCardFound extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function byId(CardId $id): self
    {
        return new self(sprintf('Could not find card by id "%s".', $id->toString()));
    }
}
