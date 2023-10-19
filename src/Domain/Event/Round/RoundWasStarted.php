<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use UnexpectedValueException;

final class RoundWasStarted implements SerializablePayload
{
    private RoundId $id;

    private PlayerId $cardCzar;

    private BlackCard $card;

    public function __construct(RoundId $id, PlayerId $cardCzar, BlackCard $card)
    {
        $this->id = $id;
        $this->cardCzar = $cardCzar;
        $this->card = $card;
    }

    public function id(): RoundId
    {
        return $this->id;
    }

    public function cardCzar(): PlayerId
    {
        return $this->cardCzar;
    }

    public function card(): BlackCard
    {
        return $this->card;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'round_id' => $this->id()->toString(),
            'card_czar_id' => $this->cardCzar()->toString(),
            'card_id' => $this->card()->id()->toString(),
            'card_content' => $this->card()->contents()->toString(),
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
        if (!is_string($payload['round_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported round_id to be a string, %s received.', gettype($payload['round_id']))
            );
        }

        if (!is_string($payload['card_czar_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported card_czar_id to be a string, %s received.', gettype($payload['card_czar_id']))
            );
        }

        if (!is_string($payload['card_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported card_id to be a string, %s received.', gettype($payload['card_id']))
            );
        }

        if (!is_string($payload['card_content'])) {
            throw new UnexpectedValueException(
                sprintf('Exported card_content to be a string, %s received.', gettype($payload['card_content']))
            );
        }

        return new self(
            RoundId::fromString($payload['round_id']),
            PlayerId::fromString($payload['card_czar_id']),
            new BlackCard(
                CardId::fromString($payload['card_id']),
                Text::fromString($payload['card_content'])
            )
        );
    }
}
