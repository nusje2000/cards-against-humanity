<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Player;

use Nusje2000\CAH\Domain\Event\Player\PlayerHasJoined;
use Nusje2000\CAH\Domain\Player\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Player\PlayerHasJoined
 */
final class PlayerHasJoinedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new PlayerHasJoined(Id::fromString('player'));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'player_id' => 'player',
        ]);

        $newEvent = PlayerHasJoined::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
