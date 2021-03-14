<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Card;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Card\BlackCard
 */
final class BlackCardTest extends TestCase
{
    public function testId(): void
    {
        self::assertEquals(Id::fromString('card'), $this->createSubject()->id());
    }

    public function testContents(): void
    {
        self::assertEquals(Text::fromString('text'), $this->createSubject()->contents());
    }

    private function createSubject(): BlackCard
    {
        return new BlackCard(Id::fromString('card'), Text::fromString('text'));
    }
}
