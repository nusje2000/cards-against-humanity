<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Game;

use LogicException;
use Throwable;

final class NoTableFound extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function create(?Throwable $previous = null): self
    {
        return new self('No table was found.', $previous);
    }
}