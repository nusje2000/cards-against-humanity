<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Game;

use LogicException;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class NoRulesFound extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function create(?Throwable $previous = null): self
    {
        return new self('No rules were found.', $previous);
    }
}
