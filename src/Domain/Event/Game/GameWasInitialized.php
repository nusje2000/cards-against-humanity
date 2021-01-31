<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Game;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\ArrayDiscardPile;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Table;

final class GameWasInitialized implements SerializablePayload
{
    /**
     * @var Table
     */
    private Table $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function table(): Table
    {
        return $this->table;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'table' => [
                'black_deck' => array_map(static function (WhiteCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->table()->whiteDeck()->cards()),
                'white_deck' => array_map(static function (BlackCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->table()->blackDeck()->cards()),
                'white_discard_pile' => array_map(static function (WhiteCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->table()->whiteDiscardPile()->cards()),
                'black_discard_pile' => array_map(static function (BlackCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $this->table()->blackDiscardPile()->cards()),
            ],
        ];
    }

    /**
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            Table::createWithDiscardPiles(
                ArrayDeck::fromArray(
                    array_map(static function (array $card): WhiteCard {
                        return new WhiteCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                    }, $payload['table']['black_deck'])
                ),
                ArrayDeck::fromArray(
                    array_map(static function (array $card): BlackCard {
                        return new BlackCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                    }, $payload['table']['white_deck'])
                ),
                ArrayDiscardPile::fromArray(
                    array_map(static function (array $card): WhiteCard {
                        return new WhiteCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                    }, $payload['table']['white_discard_pile'])
                ),
                ArrayDiscardPile::fromArray(
                    array_map(static function (array $card): BlackCard {
                        return new BlackCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                    }, $payload['table']['black_discard_pile'])
                )
            )
        );
    }
}
