<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\RoundId;

final class RoundWasCompleted implements SerializablePayload
{
    private RoundId $round;

    private PlayerId $winningPlayer;

    private WhiteCard $winningCard;

    public function __construct(PlayerId $winningPlayer, WhiteCard $winningCard)
    {
        $this->winningPlayer = $winningPlayer;
        $this->winningCard = $winningCard;
    }

    public function winningPlayer(): PlayerId
    {
        return $this->winningPlayer;
    }

    public function winningCard(): WhiteCard
    {
        return $this->winningCard;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'wining_player_id' => $this->winningPlayer()->toString(),
            'winning_card' => [
                'id' => $this->winningCard->id()->toString(),
                'contents' => $this->winningCard->contents()->toString(),
            ],
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            PlayerId::fromString($payload['winning_player_id']),
            new WhiteCard(CardId::fromString($payload['winning_card']['id']), Text::fromString($payload['winning_card']['contents']))
        );
    }
}
