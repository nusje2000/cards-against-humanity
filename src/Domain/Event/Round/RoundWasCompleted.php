<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use UnexpectedValueException;

final class RoundWasCompleted implements SerializablePayload
{
    private PlayerId $winningPlayer;

    public function __construct(PlayerId $winningPlayer)
    {
        $this->winningPlayer = $winningPlayer;
    }

    public function winningPlayer(): PlayerId
    {
        return $this->winningPlayer;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'winning_player_id' => $this->winningPlayer()->toString(),
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArrayAccess
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): static
    {
        if (!is_string($payload['winning_player_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported winning_player_id to be a string, %s received.', gettype($payload['winning_player_id']))
            );
        }

        return new self(
            PlayerId::fromString($payload['winning_player_id']),
        );
    }
}
