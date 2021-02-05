<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Player;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class PlayerJoined implements SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(PlayerId::fromString($payload['player_id']));
    }
}
