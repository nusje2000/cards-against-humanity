<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Game;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use UnexpectedValueException;

final class TableWasCreated implements SerializablePayload
{
    /**
     * @var Deck<WhiteCard>
     */
    private Deck $whiteDeck;

    /**
     * @var Deck<BlackCard>
     */
    private Deck $blackDeck;

    /**
     * @param Deck<WhiteCard> $whiteDeck
     * @param Deck<BlackCard> $blackDeck
     */
    public function __construct(Deck $whiteDeck, Deck $blackDeck)
    {
        $this->whiteDeck = $whiteDeck;
        $this->blackDeck = $blackDeck;
    }

    /**
     * @return Deck<WhiteCard>
     */
    public function whiteDeck(): Deck
    {
        return clone $this->whiteDeck;
    }

    /**
     * @return Deck<BlackCard>
     */
    public function blackDeck(): Deck
    {
        return clone $this->blackDeck;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'white_deck' => array_map(static function (WhiteCard $card): array {
                return [
                    'card_id' => $card->id()->toString(),
                    'card_contents' => $card->contents()->toString(),
                ];
            }, $this->whiteDeck()->cards()),
            'black_deck' => array_map(static function (BlackCard $card): array {
                return [
                    'card_id' => $card->id()->toString(),
                    'card_contents' => $card->contents()->toString(),
                ];
            }, $this->blackDeck()->cards()),
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
        if (!is_array($payload['white_deck'])) {
            throw new UnexpectedValueException(
                sprintf('Exported white_deck to be an array, %s received.', gettype($payload['white_deck']))
            );
        }
        if (!is_array($payload['black_deck'])) {
            throw new UnexpectedValueException(
                sprintf('Exported black_deck to be an array, %s received.', gettype($payload['black_deck']))
            );
        }

        return new self(
            ArrayDeck::fromArray(
                array_map(static function (array $card): WhiteCard {
                    return new WhiteCard(CardId::fromString($card['card_id']), Text::fromString($card['card_contents']));
                }, $payload['white_deck'])
            ),
            ArrayDeck::fromArray(
                array_map(static function (array $card): BlackCard {
                    return new BlackCard(CardId::fromString($card['card_id']), Text::fromString($card['card_contents']));
                }, $payload['black_deck'])
            ),
        );
    }
}
