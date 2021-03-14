<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Card;

use Nusje2000\CAH\Domain\Card\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Card\Text
 */
final class TextTest extends TestCase
{
    public function testFromString(): void
    {
        $text = Text::fromString('some text');
        self::assertSame('some text', $text->toString());
    }
}
