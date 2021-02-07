<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Round;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Exception\Round\NoSubmissionFound;
use Nusje2000\CAH\Domain\Exception\Round\NoWinnerFound;
use Nusje2000\CAH\Domain\Exception\Round\SubmissionAlreadyPresent;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id;
use Nusje2000\CAH\Domain\Round\Round;
use Nusje2000\CAH\Domain\Submission;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Round\Round
 */
final class RoundTest extends TestCase
{
    public function testId(): void
    {
        $subject = $this->createSubject();
        self::assertEquals($subject->id(), Id::fromString('round-1'));
    }

    public function testCardCzar(): void
    {
        $subject = $this->createSubject();
        self::assertEquals($subject->cardCzar(), PlayerId::fromString('player'));
    }

    public function testBlackCard(): void
    {
        $subject = $this->createSubject();
        self::assertEquals($subject->blackCard(), new BlackCard(CardId::fromString('card'), Text::fromString('text')));
    }

    public function testSubmissions(): void
    {
        $subject = $this->createSubject();

        self::assertEquals([], $subject->submissions());
        $submission = new Submission(PlayerId::fromString('player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));
        $subject->submit($submission);
        self::assertEquals(['player' => $submission], $subject->submissions());
    }

    public function testWinner(): void
    {
        $round = $this->createSubject();
        $submission = new Submission(PlayerId::fromString('some-player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));
        $round->submit($submission);
        $round->end($submission->player());

        self::assertEquals($submission, $round->winner());
    }

    public function testWinnerWithMissingSubmission(): void
    {
        $round = $this->createSubject();

        $this->expectExceptionObject(NoSubmissionFound::byPlayer(PlayerId::fromString('some-player')));
        $round->end(PlayerId::fromString('some-player'));
    }

    public function testWinnerWithoutWinner(): void
    {
        $round = $this->createSubject();

        $this->expectExceptionObject(NoWinnerFound::create());
        $round->winner();
    }

    public function testSubmit(): void
    {
        $round = $this->createSubject();

        $submission = new Submission(PlayerId::fromString('some-player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));
        $round->submit($submission);
        self::assertEquals($submission, $round->submissionByPlayer($submission->player()));

        $this->expectExceptionObject(SubmissionAlreadyPresent::forPlayer($submission->player()));
        $round->submit($submission);
    }

    public function testSubmissionByPlayer(): void
    {
        $round = $this->createSubject();

        $submission = new Submission(PlayerId::fromString('some-player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));
        $round->submit($submission);
        self::assertEquals($submission, $round->submissionByPlayer($submission->player()));

        $this->expectExceptionObject(NoSubmissionFound::byPlayer(PlayerId::fromString('other-player')));
        $round->submissionByPlayer(PlayerId::fromString('other-player'));
    }

    private function createSubject(): Round
    {
        return new Round(
            Id::fromString('round-1'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );
    }
}
