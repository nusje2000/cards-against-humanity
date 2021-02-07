<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Deck;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;

final class WhiteCardWasDiscarded implements SerializablePayload
{
    /**
     * @var WhiteCard
     */
    private WhiteCard $card;

    public function __construct(WhiteCard $card)
    {
        $this->card = $card;
    }

    public function card(): WhiteCard
    {
        return $this->card;
    }

    /**
     * @return array<mixed>
     */
    public function toPayload(): array
    {
        return [
            'card_id' => $this->card()->id()->toString(),
            'card_contents' => $this->card()->contents()->toString(),
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            new WhiteCard(
                Id::fromString($payload['card_id']),
                Text::fromString($payload['card_contents'])
            )
        );
    }
}
