<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Player;

use Nusje2000\CAH\Domain\Player\Username;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Player\Username
 */
final class UsernameTest extends TestCase
{
    public function testFromString(): void
    {
        $text = Username::fromString('username');
        self::assertSame('username', $text->toString());
    }
}
