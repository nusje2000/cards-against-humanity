<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Round;

use LogicException;

/**
 * @codeCoverageIgnore
 */
final class NoWinnerFound extends LogicException
{
    public static function create(): self
    {
        return new self('No winner was found, maybe the round has not been completed yet.');
    }
}
