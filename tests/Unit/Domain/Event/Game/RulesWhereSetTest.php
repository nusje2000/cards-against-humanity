<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Event\Game;

use Nusje2000\CAH\Domain\Event\Game\RulesWereSet;
use Nusje2000\CAH\Domain\Game\Rules;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Game\RulesWereSet
 */
final class RulesWhereSetTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new RulesWereSet(Rules::custom(10, 5));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'hand_size' => 10,
            'max_rounds' => 5,
        ]);

        $newEvent = RulesWereSet::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
