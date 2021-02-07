<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Card\WhiteCard
 */
final class WhiteCardTest extends TestCase
{
    public function testId(): void
    {
        self::assertEquals(Id::fromString('card'), $this->createSubject()->id());
    }

    public function testContents(): void
    {
        self::assertEquals(Text::fromString('text'), $this->createSubject()->contents());
    }

    private function createSubject(): WhiteCard
    {
        return new WhiteCard(Id::fromString('card'), Text::fromString('text'));
    }
}
