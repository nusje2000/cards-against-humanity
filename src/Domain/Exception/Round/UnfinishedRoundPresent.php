<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Round;

use LogicException;
use Nusje2000\CAH\Domain\Round\Id;
use Throwable;

final class UnfinishedRoundPresent extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function create(Id $currentRound, ?Throwable $previous = null): self
    {
        return new self(sprintf('There is still an unfinished round (id: "%s")', $currentRound->toString()), $previous);
    }
}
