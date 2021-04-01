<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Player;

use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Exception\Card\NoCardFound;
use Nusje2000\CAH\Domain\Player\Hand;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Player\Hand
 */
final class HandTest extends TestCase
{
    public function testAdd(): void
    {
        $hand = $this->createSubject();
        self::assertSame(3, $hand->size());
        $hand->add(new WhiteCard(Id::fromString('card-4'), Text::fromString('Card 4')));
        self::assertSame(4, $hand->size());
    }

    public function testRemove(): void
    {
        $hand = $this->createSubject();
        $id = Id::fromString('card-1');

        self::assertSame(3, $hand->size());
        $hand->remove(new WhiteCard($id, Text::fromString('Card 1')));
        self::assertSame(2, $hand->size());

        $this->expectExceptionObject(NoCardFound::byId($id));
        $hand->remove(new WhiteCard($id, Text::fromString('Card 1')));
    }

    public function testCard(): void
    {
        $hand = $this->createSubject();

        $id = Id::fromString('card-1');
        self::assertEquals(new WhiteCard($id, Text::fromString('Card 1')), $hand->card($id));

        $this->expectExceptionObject(NoCardFound::byId(Id::fromString('card-4')));
        $hand->card(Id::fromString('card-4'));
    }

    public function testContents(): void
    {
        $hand = $this->createSubject();
        self::assertEquals([
            'card-1' => new WhiteCard(Id::fromString('card-1'), Text::fromString('Card 1')),
            'card-2' => new WhiteCard(Id::fromString('card-2'), Text::fromString('Card 2')),
            'card-3' => new WhiteCard(Id::fromString('card-3'), Text::fromString('Card 3')),
        ], $hand->contents());
    }

    public function testEmpty(): void
    {
        $hand = Hand::empty();
        self::assertSame(0, $hand->size());
    }

    private function createSubject(): Hand
    {
        return Hand::fromArray([
            new WhiteCard(Id::fromString('card-1'), Text::fromString('Card 1')),
            new WhiteCard(Id::fromString('card-2'), Text::fromString('Card 2')),
            new WhiteCard(Id::fromString('card-3'), Text::fromString('Card 3')),
        ]);
    }
}
