<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Game;

use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Game\TableWasCreated;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Game\TableWasCreated
 */
final class TableWasCreatedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new TableWasCreated($this->whiteDeck(), $this->blackDeck());

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'white_deck' => [
                ['card_id' => 'card-1', 'card_contents' => 'Card 1'],
                ['card_id' => 'card-2', 'card_contents' => 'Card 2'],
                ['card_id' => 'card-3', 'card_contents' => 'Card 3'],
                ['card_id' => 'card-4', 'card_contents' => 'Card 4'],
                ['card_id' => 'card-5', 'card_contents' => 'Card 5'],
                ['card_id' => 'card-6', 'card_contents' => 'Card 6'],
            ],
            'black_deck' => [
                ['card_id' => 'card-1', 'card_contents' => 'Card 1'],
                ['card_id' => 'card-2', 'card_contents' => 'Card 2'],
                ['card_id' => 'card-3', 'card_contents' => 'Card 3'],
            ],
        ]);

        $newEvent = TableWasCreated::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }

    /**
     * @return Deck<WhiteCard>
     */
    private function whiteDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 6) as $index) {
            $cards[] = new WhiteCard(CardId::fromString(sprintf('card-%s', $index)), Text::fromString(sprintf('Card %s', $index)));
        }

        return ArrayDeck::fromArray($cards);
    }

    /**
     * @return Deck<BlackCard>
     */
    private function blackDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 3) as $index) {
            $cards[] = new BlackCard(CardId::fromString(sprintf('card-%s', $index)), Text::fromString(sprintf('Card %s', $index)));
        }

        return ArrayDeck::fromArray($cards);
    }
}
