<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Player;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class PlayerHasDrawnCard implements SerializablePayload
{
    private PlayerId $player;

    private WhiteCard $card;

    public function __construct(PlayerId $player, WhiteCard $card)
    {
        $this->player = $player;
        $this->card = $card;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }

    public function card(): WhiteCard
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
            'card_id' => $this->card()->id()->toString(),
            'card_contents' => $this->card()->contents()->toString(),
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
            new WhiteCard(CardId::fromString($payload['card_id']), Text::fromString($payload['card_contents']))
        );
    }
}
