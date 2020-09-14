<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\Player;
use PHPStan\Testing\TestCase;

final class PlayerTest extends TestCase
{
    public function testGetHand(): void
    {
        $player = new Player();

        self::assertEmpty($player->getHand());
    }

    public function testGetScore(): void
    {
        $player = new Player();

        self::assertSame(0, $player->getScore());
        $player->addScore(10);
        self::assertSame(10, $player->getScore());
        $player->addScore(1);
        self::assertSame(11, $player->getScore());
    }
}
