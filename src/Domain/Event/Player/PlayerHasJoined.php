<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Player;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use UnexpectedValueException;

final class PlayerHasJoined implements SerializablePayload
{
    private PlayerId $player;

    public function __construct(PlayerId $player)
    {
        $this->player = $player;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'player_id' => $this->player()->toString(),
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
        if (!is_string($payload['player_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported player_id to be a string, %s received.', gettype($payload['player_id']))
            );
        }

        return new self(PlayerId::fromString($payload['player_id']));
    }
}
