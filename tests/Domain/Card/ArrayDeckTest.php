<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\Card;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Exception\Card\EmptyDeck;
use Nusje2000\CAH\Domain\Exception\Card\NoCardFound;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Card\ArrayDeck
 */
final class ArrayDeckTest extends TestCase
{
    public function testGet(): void
    {
        $subject = $this->createSubject();

        $card = $subject->get(Id::fromString('card-1'));
        self::assertSame($card->id()->toString(), 'card-1');
        self::assertSame($card->contents()->toString(), 'This is the content of card-1');

        $card = $subject->get(Id::fromString('card-3'));
        self::assertSame($card->id()->toString(), 'card-3');
        self::assertSame($card->contents()->toString(), 'This is the content of card-3');

        $card = $subject->get(Id::fromString('card-5'));
        self::assertSame($card->id()->toString(), 'card-5');
        self::assertSame($card->contents()->toString(), 'This is the content of card-5');

        $this->expectExceptionObject(NoCardFound::byId(Id::fromString('card-6')));
        $subject->get(Id::fromString('card-6'));
    }

    public function testFirst(): void
    {
        $subject = $this->createSubject();

        $card = $subject->first();
        self::assertSame($card->id()->toString(), 'card-1');
        self::assertSame($card->contents()->toString(), 'This is the content of card-1');

        $subject = ArrayDeck::fromArray([]);
        $this->expectExceptionObject(EmptyDeck::create());
        $subject->first();
    }

    public function testRemove(): void
    {
        $subject = $this->createSubject();

        $id = Id::fromString('card-1');
        $subject->remove($id);

        $this->expectExceptionObject(NoCardFound::byId($id));
        $subject->get($id);
    }

    public function testCards(): void
    {
        $subject = $this->createSubject();

        $cards = $subject->cards();

        self::assertCount(5, $cards);
        self::assertSame(['card-1', 'card-2', 'card-3', 'card-4', 'card-5'], array_keys($cards));

        self::assertSame($cards['card-1']->id()->toString(), 'card-1');
        self::assertSame($cards['card-1']->contents()->toString(), 'This is the content of card-1');
        self::assertSame($cards['card-2']->id()->toString(), 'card-2');
        self::assertSame($cards['card-2']->contents()->toString(), 'This is the content of card-2');
        self::assertSame($cards['card-3']->id()->toString(), 'card-3');
        self::assertSame($cards['card-3']->contents()->toString(), 'This is the content of card-3');
        self::assertSame($cards['card-4']->id()->toString(), 'card-4');
        self::assertSame($cards['card-4']->contents()->toString(), 'This is the content of card-4');
        self::assertSame($cards['card-5']->id()->toString(), 'card-5');
        self::assertSame($cards['card-5']->contents()->toString(), 'This is the content of card-5');
    }

    public function testSize(): void
    {
        self::assertSame(5, $this->createSubject()->size());
    }

    /**
     * @return ArrayDeck<Card>
     */
    private function createSubject(): ArrayDeck
    {
        return ArrayDeck::fromArray([
            $this->createCard('card-1'),
            $this->createCard('card-2'),
            $this->createCard('card-3'),
            $this->createCard('card-4'),
            $this->createCard('card-5'),
        ]);
    }

    private function createCard(string $id): Card
    {
        $card = $this->createStub(Card::class);
        $card->method('id')->willReturn(Id::fromString($id));
        $card->method('contents')->willReturn(Text::fromString(sprintf('This is the content of %s', $id)));

        return $card;
    }
}
