<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Event\Round;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Event\Round\RoundWasStarted;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Round\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Round\RoundWasStarted
 */
final class RoundWasStartedTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new RoundWasStarted(
            Id::fromString('round'),
            PlayerId::fromString('player'),
            new BlackCard(CardId::fromString('card'), Text::fromString('text'))
        );

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'round_id' => 'round',
            'card_czar_id' => 'player',
            'card_id' => 'card',
            'card_content' => 'text',
        ]);

        $newEvent = RoundWasStarted::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
