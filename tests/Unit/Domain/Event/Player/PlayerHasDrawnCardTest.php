<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Event\Player;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Player\PlayerHasDrawnCard;
use Nusje2000\CAH\Domain\Player\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Event\Player\PlayerHasDrawnCard
 */
final class PlayerHasDrawnCardTest extends TestCase
{
    public function testSerialization(): void
    {
        $event = new PlayerHasDrawnCard(Id::fromString('player'), new WhiteCard(CardId::fromString('card'), Text::fromString('text')));

        $payload = $event->toPayload();
        self::assertSame($payload, [
            'player_id' => 'player',
            'card_id' => 'card',
            'card_contents' => 'text',
        ]);

        $newEvent = PlayerHasDrawnCard::fromPayload($payload);

        self::assertEquals($event, $newEvent);
    }
}
