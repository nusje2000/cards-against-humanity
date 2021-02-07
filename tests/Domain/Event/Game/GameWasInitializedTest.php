<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Game;

use Nusje2000\CAH\Domain\Event\Game\GameWasInitialized;
use Nusje2000\CAH\Domain\Game\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Game\GameWasInitialized
 */
final class GameWasInitializedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new GameWasInitialized(Id::fromString('game'));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'game_id' => 'game',
        ]);

        $newEvent = GameWasInitialized::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
