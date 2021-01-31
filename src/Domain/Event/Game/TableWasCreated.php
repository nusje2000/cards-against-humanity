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
            'table' => [
                'white_deck' => array_map(static function (WhiteCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->whiteDeck()->cards()),
                'black_deck' => array_map(static function (BlackCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->blackDeck()->cards()),
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
            ArrayDeck::fromArray(
                array_map(static function (array $card): WhiteCard {
                    return new WhiteCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                }, $payload['table']['white_deck'])
            ),
            ArrayDeck::fromArray(
                array_map(static function (array $card): BlackCard {
                    return new BlackCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                }, $payload['table']['black_deck'])
            ),
        );
    }
}
