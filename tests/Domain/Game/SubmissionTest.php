<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Card\AnswerInterface;
use Nusje2000\CAH\Domain\Game\PlayerInterface;
use Nusje2000\CAH\Domain\Game\Submission;
use PHPStan\Testing\TestCase;

final class SubmissionTest extends TestCase
{
    public function testSubmission(): void
    {
        $player = $this->createStub(PlayerInterface::class);
        $answer = $this->createStub(AnswerInterface::class);

        $submission = new Submission($player, $answer);

        self::assertSame($player, $submission->getPlayer());
        self::assertSame($answer, $submission->getAnswer());
    }

    public function testJsonSerialize(): void
    {
        $player = $this->createStub(PlayerInterface::class);
        $answer = $this->createStub(AnswerInterface::class);

        $submission = new Submission($player, $answer);

        self::assertSame($submission->jsonSerialize(), [
            'player' => $player,
            'answer' => $answer,
        ]);
    }
}
