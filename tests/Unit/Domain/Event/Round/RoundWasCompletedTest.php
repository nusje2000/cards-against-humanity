<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Event\Round;

use Nusje2000\CAH\Domain\Event\Round\RoundWasCompleted;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Round\RoundWasCompleted
 */
final class RoundWasCompletedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new RoundWasCompleted(PlayerId::fromString('player'));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'winning_player_id' => 'player',
        ]);

        $newEvent = RoundWasCompleted::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
