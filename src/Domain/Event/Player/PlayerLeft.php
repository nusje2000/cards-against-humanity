<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Player;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class PlayerLeft implements SerializablePayload
{
    private PlayerId $playerId;

    public function __construct(PlayerId $playerId)
    {
        $this->playerId = $playerId;
    }

    public function id(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'player_id' => $this->id()->toString(),
        ];
    }

    /**
     *
     * @psalm-suppress MixedArgument
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            PlayerId::fromString($payload['player_id'])
        );
    }
}
