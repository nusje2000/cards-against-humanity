<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain;

use Nusje2000\CAH\Domain\Card\ArrayDiscardPile;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\DiscardPile;
use Nusje2000\CAH\Domain\Card\WhiteCard;

final class Table
{
    /**
     * @var Deck<WhiteCard>
     */
    private Deck $whiteDeck;

    /**
     * @var Deck<BlackCard>
     */
    private Deck $blackDeck;

    /**
     * @var DiscardPile<WhiteCard>
     */
    private DiscardPile $whiteDiscardPile;

    /**
     * @var DiscardPile<BlackCard>
     */
    private DiscardPile $blackDiscardPile;

    /**
     * @param Deck<WhiteCard>        $whiteDeck
     * @param Deck<BlackCard>        $blackDeck
     * @param DiscardPile<WhiteCard> $whiteDiscardPile
     * @param DiscardPile<BlackCard> $blackDiscardPile
     */
    private function __construct(Deck $whiteDeck, Deck $blackDeck, DiscardPile $whiteDiscardPile, DiscardPile $blackDiscardPile)
    {
        $this->whiteDeck = $whiteDeck;
        $this->blackDeck = $blackDeck;
        $this->whiteDiscardPile = $whiteDiscardPile;
        $this->blackDiscardPile = $blackDiscardPile;
    }

    /**
     * @param Deck<WhiteCard> $whiteDeck
     * @param Deck<BlackCard> $blackDeck
     */
    public static function create(Deck $whiteDeck, Deck $blackDeck): self
    {
        /** @var DiscardPile<WhiteCard> $whiteDiscardPile */
        $whiteDiscardPile = ArrayDiscardPile::empty();
        /** @var DiscardPile<BlackCard> $blackDiscardPile */
        $blackDiscardPile = ArrayDiscardPile::empty();

        return self::createWithDiscardPiles($whiteDeck, $blackDeck, $whiteDiscardPile, $blackDiscardPile);
    }

    /**
     * @param Deck<WhiteCard>        $whiteDeck
     * @param Deck<BlackCard>        $blackDeck
     * @param DiscardPile<WhiteCard> $whiteDiscardPile
     * @param DiscardPile<BlackCard> $blackDiscardPile
     */
    public static function createWithDiscardPiles(Deck $whiteDeck, Deck $blackDeck, DiscardPile $whiteDiscardPile, DiscardPile $blackDiscardPile): self
    {
        return new self($whiteDeck, $blackDeck, $whiteDiscardPile, $blackDiscardPile);
    }

    /**
     * @return Deck<WhiteCard>
     */
    public function whiteDeck(): Deck
    {
        return $this->whiteDeck;
    }

    /**
     * @return Deck<BlackCard>
     */
    public function blackDeck(): Deck
    {
        return $this->blackDeck;
    }

    /**
     * @return DiscardPile<WhiteCard>
     */
    public function whiteDiscardPile(): DiscardPile
    {
        return $this->whiteDiscardPile;
    }

    /**
     * @return DiscardPile<BlackCard>
     */
    public function blackDiscardPile(): DiscardPile
    {
        return $this->blackDiscardPile;
    }
}
