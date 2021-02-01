<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id as RoundId;

final class RoundWasStarted implements SerializablePayload
{
    private RoundId $id;

    private PlayerId $cardCzar;

    private BlackCard $blackCard;

    public function __construct(RoundId $id, PlayerId $cardCzar, BlackCard $blackCard)
    {
        $this->id = $id;
        $this->cardCzar = $cardCzar;
        $this->blackCard = $blackCard;
    }

    public function id(): RoundId
    {
        return $this->id;
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
            'id' => $this->id()->toString(),
            'card_czar' => $this->cardCzar()->toString(),
            'black_card' => [
                'id' => $this->blackCard()->id()->toString(),
                'contents' => $this->blackCard()->contents()->toString(),
            ],
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
            RoundId::fromString($payload['id']),
            PlayerId::fromString($payload['card_czar']),
            new BlackCard(CardId::fromString($payload['black_card']['id']), Text::fromString($payload['black_card']['contents'])),
        );
    }
}
