<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Round;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Exception\Game\NoActiveRound;
use Nusje2000\CAH\Domain\Exception\Round\UnfinishedRoundPresent;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id;
use Nusje2000\CAH\Domain\Round\Registry;
use Nusje2000\CAH\Domain\Round\Round;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Round\Registry
 */
final class RegistryTest extends TestCase
{
    public function testStart(): void
    {
        $subject = $this->createSubject();
        $round = new Round(
            Id::fromString('round-1'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );

        $subject->start($round);
        self::assertSame($round, $subject->current());

        $this->expectExceptionObject(UnfinishedRoundPresent::create($round->id()));
        $subject->start($this->createRound());
    }

    public function testFinishCurrentRound(): void
    {
        $subject = $this->createSubject();
        $round = new Round(
            Id::fromString('round-1'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );

        $subject->start($round);
        $subject->finishCurrentRound();

        $this->expectExceptionObject(NoActiveRound::create());
        $subject->current();
    }

    public function testCompleted(): void
    {
        $subject = $this->createSubject();
        $completedRound = $this->createRound();

        $subject->start($completedRound);
        $subject->finishCurrentRound();
        $subject->start($this->createRound());

        self::assertSame([$completedRound], $subject->completed());
    }

    public function testHasActiveRound(): void
    {
        $subject = $this->createSubject();

        self::assertFalse($subject->hasActiveRound());
        $subject->start($this->createRound());
        self::assertTrue($subject->hasActiveRound());
        $subject->finishCurrentRound();
        self::assertFalse($subject->hasActiveRound());
    }

    public function testCurrent(): void
    {
        $subject = $this->createSubject();

        $round = $this->createRound();
        $subject->start($round);
        self::assertSame($round, $subject->current());
        $subject->finishCurrentRound();

        $this->expectExceptionObject(NoActiveRound::create());
        $subject->current();
    }

    public function testPrevious(): void
    {
        $subject = $this->createSubject();

        $round = $this->createRound();
        $subject->start($round);
        $subject->finishCurrentRound();

        self::assertSame($round, $subject->previous());
    }

    public function testJsonSerialize(): void
    {
        $subject = $this->createSubject();

        self::assertEquals([
            'current' => null,
            'completed' => [],
            'previous' => null,
        ], $subject->jsonSerialize());

        $round = new Round(
            Id::fromString('round-1'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );

        $subject->start($round);

        self::assertEquals([
            'current' => $round,
            'completed' => [],
            'previous' => null,
        ], $subject->jsonSerialize());

        $subject->finishCurrentRound();

        self::assertEquals([
            'current' => null,
            'completed' => [$round],
            'previous' => $round,
        ], $subject->jsonSerialize());
    }

    private function createSubject(): Registry
    {
        return new Registry();
    }

    private function createRound(): Round
    {
        return new Round(
            Id::fromString('round-1'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );
    }
}
