<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use LogicException;
use Nusje2000\CAH\Domain\Card\QuestionInterface;
use Nusje2000\CAH\Domain\Game\PlayerInterface;
use Nusje2000\CAH\Domain\Game\Round;
use Nusje2000\CAH\Domain\Game\SubmissionInterface;
use PHPStan\Testing\TestCase;

final class RoundTest extends TestCase
{
    public function testGetQuestion(): void
    {
        $question = $this->createStub(QuestionInterface::class);
        $round = new Round($this->createStub(PlayerInterface::class), $question);

        self::assertSame($question, $round->getQuestion());
    }

    public function testGetCardCzar(): void
    {
        $player = $this->createStub(PlayerInterface::class);
        $round = new Round($player, $this->createStub(QuestionInterface::class));

        self::assertSame($player, $round->getCardCzar());
    }

    public function testGetSubmissions(): void
    {
        $round = new Round($this->createStub(PlayerInterface::class), $this->createStub(QuestionInterface::class));

        $submission = $this->createStub(SubmissionInterface::class);

        self::assertEmpty($round->getSubmissions());
        $round->getSubmissions()->append($submission);
        self::assertSame([$submission], $round->getSubmissions()->toArray());
    }

    public function testIsCompleted(): void
    {
        $round = new Round($this->createStub(PlayerInterface::class), $this->createStub(QuestionInterface::class));

        self::assertFalse($round->isCompleted());
        $round->setWinner($this->createStub(SubmissionInterface::class));
        self::assertTrue($round->isCompleted());
    }

    public function testGetWinner(): void
    {
        $round = new Round($this->createStub(PlayerInterface::class), $this->createStub(QuestionInterface::class));

        $winner = $this->createStub(SubmissionInterface::class);
        $round->setWinner($winner);
        self::assertSame($winner, $round->getWinner());
    }

    public function testGetWinnerWithoutSetWinner(): void
    {
        $round = new Round($this->createStub(PlayerInterface::class), $this->createStub(QuestionInterface::class));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No winner was selected yet.');
        $round->getWinner();
    }

    public function testJsonSerialize(): void
    {
        $cardCzar = $this->createStub(PlayerInterface::class);
        $question = $this->createStub(QuestionInterface::class);

        $round = new Round($cardCzar, $question);

        self::assertSame([
            'card_czar' => $cardCzar,
            'question' => $question,
            'submissions' => $round->getSubmissions(),
            'winner' => null,
            'completed' => false,
        ], $round->jsonSerialize());

        $winner = $this->createStub(SubmissionInterface::class);
        $round->setWinner($winner);

        self::assertSame([
            'card_czar' => $cardCzar,
            'question' => $question,
            'submissions' => $round->getSubmissions(),
            'winner' => $winner,
            'completed' => true,
        ], $round->jsonSerialize());
    }
}
