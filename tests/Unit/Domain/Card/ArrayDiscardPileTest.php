<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Card;

use Nusje2000\CAH\Domain\Card\ArrayDiscardPile;
use Nusje2000\CAH\Domain\Card\Card;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Card\ArrayDiscardPile
 */
final class ArrayDiscardPileTest extends TestCase
{
    public function testEmpty(): void
    {
        $pile = ArrayDiscardPile::empty();
        self::assertSame([], $pile->cards());
    }

    public function testDiscard(): void
    {
        $subject = $this->createSubject();
        $subject->discard($this->createCard('card-3'));
        self::assertSame(3, $subject->size());
    }

    public function testCards(): void
    {
        $subject = $this->createSubject();
        $cards = $subject->cards();

        self::assertCount(2, $cards);
        self::assertSame($cards[0]->id()->toString(), 'card-1');
        self::assertSame($cards[0]->contents()->toString(), 'This is the content of card-1');
        self::assertSame($cards[1]->id()->toString(), 'card-2');
        self::assertSame($cards[1]->contents()->toString(), 'This is the content of card-2');
    }

    public function testSize(): void
    {
        $subject = $this->createSubject();
        self::assertSame(2, $subject->size());
        $subject->discard($this->createCard('card-3'));
        self::assertSame(3, $subject->size());
        $subject->discard($this->createCard('card-4'));
        self::assertSame(4, $subject->size());
    }

    /**
     * @return ArrayDiscardPile<Card>
     */
    private function createSubject(): ArrayDiscardPile
    {
        return ArrayDiscardPile::fromArray([
            $this->createCard('card-1'),
            $this->createCard('card-2'),
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
