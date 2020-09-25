<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use LogicException;
use Nusje2000\CAH\Domain\Card\Deck\AnswerDeckInterface;
use Nusje2000\CAH\Domain\Card\Deck\QuestionDeckInterface;
use Nusje2000\CAH\Domain\Game\Game;
use Nusje2000\CAH\Domain\Game\PlayerCollection;
use Nusje2000\CAH\Domain\Game\PlayerInterface;
use Nusje2000\CAH\Domain\Game\RoundCollection;
use Nusje2000\CAH\Domain\Game\RoundInterface;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGetPlayers(): void
    {
        $players = new PlayerCollection();

        $game = new Game(
            $players,
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        self::assertSame($players, $game->getPlayers());
    }

    public function testGetQuestionDeck(): void
    {
        $deck = $this->createStub(QuestionDeckInterface::class);

        $game = new Game(
            new PlayerCollection(),
            $deck,
            $this->createStub(AnswerDeckInterface::class),
        );

        self::assertSame($deck, $game->getQuestionDeck());
    }

    public function testGetAnswerDeck(): void
    {
        $deck = $this->createStub(AnswerDeckInterface::class);

        $game = new Game(
            new PlayerCollection(),
            $this->createStub(QuestionDeckInterface::class),
            $deck,
        );

        self::assertSame($deck, $game->getAnswerDeck());
    }

    public function testGetCurrentRound(): void
    {
        $game = new Game(
            new PlayerCollection(),
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        self::assertEmpty($game->getPreviousRounds());

        $firstRound = $this->createStub(RoundInterface::class);
        $game->setNextRound($firstRound);

        self::assertSame($firstRound, $game->getCurrentRound());
        self::assertEmpty($game->getPreviousRounds());

        $secondRound = $this->createStub(RoundInterface::class);
        $game->setNextRound($secondRound);

        self::assertSame($secondRound, $game->getCurrentRound());
        self::assertSame([$firstRound], $game->getPreviousRounds()->toArray());
    }

    public function testGetCurrentRoundWithoutRounds(): void
    {
        $game = new Game(
            new PlayerCollection(),
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No rounds set.');
        $game->getCurrentRound();
    }

    public function testIsStarted(): void
    {
        $game = new Game(
            new PlayerCollection(),
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        self::assertFalse($game->isStarted());
        $game->setNextRound($this->createStub(RoundInterface::class));
        self::assertTrue($game->isStarted());
    }

    public function testJsonSerialize(): void
    {
        $players = new PlayerCollection([
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
        ]);

        $game = new Game(
            $players,
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        $serialized = $game->jsonSerialize();

        self::assertArrayHasKey('players', $serialized);
        self::assertSame($players, $serialized['players']);

        self::assertArrayHasKey('current_round', $serialized);
        self::assertNull($serialized['current_round']);

        self::assertArrayHasKey('previous_rounds', $serialized);
        self::assertInstanceOf(RoundCollection::class, $serialized['previous_rounds']);
        self::assertSame([], $serialized['previous_rounds']->toArray());
    }

    public function testJsonSerializeWithStartedGame(): void
    {
        $game = new Game(
            new PlayerCollection(),
            $this->createStub(QuestionDeckInterface::class),
            $this->createStub(AnswerDeckInterface::class),
        );

        $firstRound = $this->createStub(RoundInterface::class);
        $game->setNextRound($firstRound);
        $secondRound = $this->createStub(RoundInterface::class);
        $game->setNextRound($secondRound);

        $serialized = $game->jsonSerialize();

        self::assertArrayHasKey('current_round', $serialized);
        self::assertSame($secondRound, $serialized['current_round']);

        self::assertArrayHasKey('previous_rounds', $serialized);
        self::assertInstanceOf(RoundCollection::class, $serialized['previous_rounds']);
        self::assertSame([$firstRound], $serialized['previous_rounds']->toArray());
    }
}
