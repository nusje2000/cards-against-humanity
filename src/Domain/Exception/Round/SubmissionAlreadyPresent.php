<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Exception\Round;

use DomainException;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class SubmissionAlreadyPresent extends DomainException
{
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function forPlayer(PlayerId $player): self
    {
        return new self(sprintf('Player "%s" has already submitted a card.', $player->toString()));
    }
}
