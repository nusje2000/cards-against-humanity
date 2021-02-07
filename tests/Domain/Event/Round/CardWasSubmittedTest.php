<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Round;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Event\Round\CardWasSubmitted;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Round\CardWasSubmitted
 */
final class CardWasSubmittedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new CardWasSubmitted(PlayerId::fromString('player'), CardId::fromString('card'));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'player_id' => 'player',
            'card_id' => 'card',
        ]);

        $newEvent = CardWasSubmitted::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
