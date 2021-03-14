<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Submission;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Submission
 */
final class SubmissionTest extends TestCase
{
    public function testPlayer(): void
    {
        self::assertEquals(PlayerId::fromString('player'), $this->createSubject()->player());
    }

    public function testCard(): void
    {
        self::assertEquals(new WhiteCard(CardId::fromString('card'), Text::fromString('text')), $this->createSubject()->card());
    }

    private function createSubject(): Submission
    {
        return new Submission(PlayerId::fromString('player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));
    }
}
