<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Card;

use LogicException;
use Throwable;

final class EmptyDeck extends LogicException
{
    private function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }

    public static function create(Throwable $previous = null): self
    {
        return new self('The deck is emtpy.', $previous);
    }
}
