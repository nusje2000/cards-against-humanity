<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Deck;

use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Deck\WhiteCardWasDiscarded;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Deck\WhiteCardWasDiscarded
 */
final class WhiteCardWasDiscardedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new WhiteCardWasDiscarded(
            new WhiteCard(
                Id::fromString('card'),
                Text::fromString('contents')
            )
        );

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'card_id' => 'card',
            'card_contents' => 'contents',
        ]);

        $newEvent = WhiteCardWasDiscarded::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
