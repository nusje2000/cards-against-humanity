<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Generator;
use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Game\Rules;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use PHPUnit\Framework\TestCase;

final class EventBasedGameTest extends TestCase
{
    public function testGame(): void
    {
        $player1 = Id::fromString('player-1');
        $player2 = Id::fromString('player-2');

        $game = EventBasedGame::initialize(Id::fromString('some-id'), Rules::custom(4, 4), $this->whiteDeck(), $this->blackDeck());
        $game->join(Id::fromString('player-1'));
        $game->join(Id::fromString('player-2'));

        $game->startRound(RoundId::fromString('round-1'));
        self::assertSame(['card-1', 'card-2', 'card-3', 'card-4'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-5', 'card-6', 'card-7', 'card-8'], array_keys($game->hand($player2)->contents()));
        self::assertTrue($game->rounds()->current()->cardCzar()->isEqualTo($player1));
        $game->submit($player2, CardId::fromString('card-5'));
        $game->completeRound($player2);

        $game->startRound(RoundId::fromString('round-2'));
        self::assertSame(['card-1', 'card-2', 'card-3', 'card-4'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-6', 'card-7', 'card-8', 'card-9'], array_keys($game->hand($player2)->contents()));
        self::assertTrue($game->rounds()->current()->cardCzar()->isEqualTo($player2));
        $game->submit($player1, CardId::fromString('card-1'));
        $game->completeRound($player1);

        self::assertSame(['card-2', 'card-3', 'card-4', 'card-10'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-6', 'card-7', 'card-8', 'card-9'], array_keys($game->hand($player2)->contents()));

        $generator = $this->createEventGenerator($game->releaseEvents());
        $game = clone $game;
        $reconstitutedGame = EventBasedGame::reconstituteFromEvents($game->id(), $generator);

        self::assertEquals($game, $reconstitutedGame);
    }

    /**
     * @return Deck<WhiteCard>
     */
    private function whiteDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 20) as $index) {
            $cards[] = new WhiteCard(CardId::fromString(sprintf('card-%d', $index)), Text::fromString(sprintf('Card %d', $index)));
        }

        return ArrayDeck::fromArray($cards);
    }

    /**
     * @return Deck<BlackCard>
     */
    private function blackDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 5) as $index) {
            $cards[] = new BlackCard(CardId::fromString(sprintf('card-%d', $index)), Text::fromString(sprintf('Card %d', $index)));
        }

        return ArrayDeck::fromArray($cards);
    }

    /**
     * @return Generator<object>
     */
    private function createEventGenerator(array $events): Generator
    {
        foreach ($events as $item) {
            yield $item;
        }

        return count($events);
    }
}
