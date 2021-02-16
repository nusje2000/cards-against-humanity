<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Round;

use LogicException;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class NoSubmissionFound extends LogicException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function byPlayer(PlayerId $id): self
    {
        return new self(sprintf('Could not find submission by player "%s".', $id->toString()));
    }
}
