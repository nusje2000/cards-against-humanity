<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain;

use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\ArrayDiscardPile;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Table;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Table
 */
final class TableTest extends TestCase
{
    public function testCreateWith(): void
    {
        /** @var ArrayDeck<WhiteCard> $whiteDeck */
        $whiteDeck = ArrayDeck::fromArray([]);
        /** @var ArrayDeck<BlackCard> $blackDeck */
        $blackDeck = ArrayDeck::fromArray([]);

        $table = Table::create($whiteDeck, $blackDeck);

        self::assertSame($whiteDeck, $table->whiteDeck());
        self::assertSame($blackDeck, $table->blackDeck());
        self::assertEquals(ArrayDiscardPile::fromArray([]), $table->whiteDiscardPile());
        self::assertEquals(ArrayDiscardPile::fromArray([]), $table->blackDiscardPile());
    }

    public function testCreateWithDiscardPiles(): void
    {
        /** @var ArrayDeck<WhiteCard> $whiteDeck */
        $whiteDeck = ArrayDeck::fromArray([]);
        /** @var ArrayDeck<BlackCard> $blackDeck */
        $blackDeck = ArrayDeck::fromArray([]);
        /** @var ArrayDiscardPile<WhiteCard> $whiteDeck */
        $whiteDiscardPile = ArrayDiscardPile::fromArray([]);
        /** @var ArrayDiscardPile<BlackCard> $blackDiscardPile */
        $blackDiscardPile = ArrayDiscardPile::fromArray([]);

        $table = Table::createWithDiscardPiles($whiteDeck, $blackDeck, $whiteDiscardPile, $blackDiscardPile);

        self::assertSame($whiteDeck, $table->whiteDeck());
        self::assertSame($blackDeck, $table->blackDeck());
        self::assertSame($whiteDiscardPile, $table->whiteDiscardPile());
        self::assertSame($blackDiscardPile, $table->blackDiscardPile());
    }
}
