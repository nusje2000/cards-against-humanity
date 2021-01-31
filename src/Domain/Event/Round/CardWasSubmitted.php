<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class CardWasSubmitted implements SerializablePayload
{
    private PlayerId $player;

    private CardId $card;

    public function __construct(PlayerId $player, CardId $card)
    {

        $this->player = $player;
        $this->card = $card;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }

    public function card(): CardId
    {
        return $this->card;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'player_id' => $this->player()->toString(),
            'card_id' => $this->card()->toString(),
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
        return new self(
            PlayerId::fromString($payload['player_id']),
            CardId::fromString($payload['card_id']),
        );
    }
}
