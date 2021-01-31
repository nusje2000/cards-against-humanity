<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class RoundWasStarted implements SerializablePayload
{
    /**
     * @var PlayerId
     */
    private PlayerId $cardCzar;

    /**
     * @var BlackCard
     */
    private BlackCard $blackCard;

    public function __construct(PlayerId $cardCzar, BlackCard $blackCard)
    {
        $this->cardCzar = $cardCzar;
        $this->blackCard = $blackCard;
    }

    public function cardCzar(): PlayerId
    {
        return $this->cardCzar;
    }

    public function blackCard(): BlackCard
    {
        return $this->blackCard;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'card_czar' => $this->cardCzar(),
            'black_card' => [
                'id' => $this->blackCard()->id()->toString(),
                'contents' => $this->blackCard()->contents()->toString(),
            ],
        ];
    }

    /**
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            PlayerId::fromString($payload['id']),
            new BlackCard(CardId::fromString($payload['black_card']['id']), Text::fromString($payload['black_card']['contents'])),
        );
    }
}
