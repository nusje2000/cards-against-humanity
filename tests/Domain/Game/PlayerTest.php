<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\Player;
use PHPStan\Testing\TestCase;

final class PlayerTest extends TestCase
{
    public function testGetName(): void
    {
        $player = new Player('some-name');

        self::assertSame('some-name', $player->getName());
    }

    public function testGetHand(): void
    {
        $player = new Player('some-name');

        self::assertEmpty($player->getHand());
    }

    public function testGetScore(): void
    {
        $player = new Player('some-name');

        self::assertSame(0, $player->getScore());
        $player->addScore(10);
        self::assertSame(10, $player->getScore());
        $player->addScore(1);
        self::assertSame(11, $player->getScore());
    }

    public function testJsonSerialize(): void
    {
        $player = new Player('some-name');

        self::assertSame([
            'name' => 'some-name',
            'score' => 0,
            'hand' => $player->getHand(),
        ], $player->jsonSerialize());

        $player->addScore(10);

        self::assertSame([
            'name' => 'some-name',
            'score' => 10,
            'hand' => $player->getHand(),
        ], $player->jsonSerialize());
    }
}
