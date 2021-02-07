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
use Nusje2000\CAH\Domain\Exception\Game\NoRulesFound;
use Nusje2000\CAH\Domain\Exception\Game\NoTableFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Domain\Exception\Game\RoundLimitReached;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Game\Rules;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Game\EventBasedGame
 */
final class EventBasedGameTest extends TestCase
{
    public function testGame(): void
    {
        $player1 = PlayerId::fromString('player-1');
        $player2 = PlayerId::fromString('player-2');

        $whiteDeck = $this->whiteDeck();
        $blackDeck = $this->blackDeck();

        $game = $this->createSubject(null, $whiteDeck, $blackDeck);

        self::assertEquals($whiteDeck, $game->table()->whiteDeck());
        self::assertEquals($blackDeck, $game->table()->blackDeck());
        self::assertSame(4, $game->rules()->handSize());
        self::assertSame(2, $game->rules()->maxRounds());

        $game->join($player1);
        $game->draw($player1);
        $game->draw($player1);
        $game->draw($player1);

        $game->join($player2);
        $game->draw($player2);
        $game->draw($player2);
        $game->draw($player2);

        self::assertEquals(['player-1' => $player1, 'player-2' => $player2], $game->players());

        $game->startRound(RoundId::fromString('round-1'));
        self::assertSame(['card-1', 'card-2', 'card-3'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-4', 'card-5', 'card-6'], array_keys($game->hand($player2)->contents()));
        self::assertTrue($game->rounds()->current()->cardCzar()->isEqualTo($player1));
        $game->submit($player2, CardId::fromString('card-5'));
        $game->completeRound($player2);
        $game->draw($player2);

        $game->startRound(RoundId::fromString('round-2'));
        self::assertSame(['card-1', 'card-2', 'card-3'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-4', 'card-6', 'card-7'], array_keys($game->hand($player2)->contents()));
        self::assertTrue($game->rounds()->current()->cardCzar()->isEqualTo($player2));
        $game->submit($player1, CardId::fromString('card-1'));
        $game->completeRound($player1);
        $game->draw($player1);

        self::assertSame(['card-2', 'card-3', 'card-8'], array_keys($game->hand($player1)->contents()));
        self::assertSame(['card-4', 'card-6', 'card-7'], array_keys($game->hand($player2)->contents()));

        $game->leave($player1);
        $game->leave($player2);

        self::assertEquals([], $game->players());

        $generator = $this->createEventGenerator($game->releaseEvents());
        $game = clone $game;
        $reconstitutedGame = EventBasedGame::reconstituteFromEvents($game->id(), $generator);

        self::assertEquals($game, $reconstitutedGame);
    }

    public function testTableWithoutTableSet(): void
    {
        /** @var EventBasedGame $game */
        $game = $this->createEmptySubject();

        $this->expectExceptionObject(NoTableFound::create());
        $game->table();
    }

    public function testRulesWithoutRulesSet(): void
    {
        /** @var EventBasedGame $game */
        $game = $this->createEmptySubject();

        $this->expectExceptionObject(NoRulesFound::create());
        $game->rules();
    }

    public function testHand(): void
    {
        $player1 = PlayerId::fromString('player-1');

        $game = $this->createSubject();
        $game->join($player1);
        $game->draw($player1);

        self::assertEquals(['card-1'], array_keys($game->hand($player1)->contents()));

        $nonExistentPlayer = PlayerId::fromString('player-2');
        $this->expectExceptionObject(PlayerDoesNotExist::withId($nonExistentPlayer));
        $game->hand($nonExistentPlayer);
    }

    public function testSubmit(): void
    {
        $player1 = PlayerId::fromString('player-1');
        $player2 = PlayerId::fromString('player-2');

        $game = $this->createSubject();
        $game->join($player1);
        $game->join($player2);
        $game->draw($player2);
        $game->startRound(RoundId::fromString('round-1'));
        $game->submit($player2, CardId::fromString('card-1'));

        self::assertTrue($game->rounds()->current()->playerHasSubmitted($player2));

        $nonExistentPlayer = PlayerId::fromString('player-3');
        $this->expectExceptionObject(PlayerDoesNotExist::withId($nonExistentPlayer));
        $game->submit($nonExistentPlayer, CardId::fromString('card-2'));
    }

    public function testStartRound(): void
    {
        $player1 = PlayerId::fromString('player-1');

        $game = $this->createSubject(Rules::custom(1, 0));
        $game->join($player1);
        $this->expectExceptionObject(RoundLimitReached::create());
        $game->startRound(RoundId::fromString('round'));
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

    /**
     * @param Deck<WhiteCard>|null $whiteDeck
     * @param Deck<BlackCard>|null $blackDeck
     */
    private function createSubject(?Rules $rules = null, ?Deck $whiteDeck = null, ?Deck $blackDeck = null): EventBasedGame
    {
        return EventBasedGame::initialize(
            Id::fromString('some-id'),
            $rules ?? Rules::custom(4, 2),
            $whiteDeck ?? $this->whiteDeck(),
            $blackDeck ?? $this->blackDeck()
        );
    }

    private function createEmptySubject(): EventBasedGame
    {
        /** @var EventBasedGame $game */
        $game = EventBasedGame::reconstituteFromEvents(Id::fromString('game'), $this->createEventGenerator([]));

        return $game;
    }
}
