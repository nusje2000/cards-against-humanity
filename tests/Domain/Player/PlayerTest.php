<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Player;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\Username;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Player\Player
 */
final class PlayerTest extends TestCase
{
    public function testCreate(): void
    {
        $player = new Player(Id::fromString('id'), Username::fromString('username'));
        self::assertSame('id', $player->id()->toString());
        self::assertSame('username', $player->username()->toString());
    }
}
