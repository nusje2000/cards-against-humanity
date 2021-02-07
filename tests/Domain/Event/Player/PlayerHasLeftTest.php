<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Player;

use Nusje2000\CAH\Domain\Event\Player\PlayerHasLeft;
use Nusje2000\CAH\Domain\Player\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Player\PlayerHasLeft
 */
final class PlayerHasLeftTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new PlayerHasLeft(Id::fromString('player'));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'player_id' => 'player',
        ]);

        $newEvent = PlayerHasLeft::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
