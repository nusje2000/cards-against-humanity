<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Event\Deck;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Event\Deck\BlackCardWasDiscarded;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Deck\BlackCardWasDiscarded
 */
final class BlackCardWasDiscardedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new BlackCardWasDiscarded(
            new BlackCard(
                Id::fromString('card'),
                Text::fromString('contents')
            )
        );

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'card_id' => 'card',
            'card_contents' => 'contents',
        ]);

        $newEvent = BlackCardWasDiscarded::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
