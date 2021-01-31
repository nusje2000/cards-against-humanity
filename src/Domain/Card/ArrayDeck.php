<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use JsonSerializable;
use Nusje2000\CAH\Domain\Exception\Card\EmptyDeck;

/**
 * @template T of Card
 *
 * @implements Deck<T>
 */
final class ArrayDeck implements Deck, JsonSerializable
{
    /**
     * @var array<array-key, T> $cards
     */
    private array $cards;

    /**
     * @param array<array-key, T> $cards
     */
    private function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    /**
     * @template CardType of Card
     *
     * @param array<array-key, CardType> $cards
     *
     * @return self<CardType>
     */
    public static function fromArray(array $cards): self
    {
        return new self($cards);
    }

    /**
     * @return T
     */
    public function draw(): Card
    {
        /** @var T|null $card */
        $card = array_shift($this->cards);

        if (null !== $card) {
            return $card;
        }

        throw EmptyDeck::create();
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function size(): int
    {
        return count($this->cards);
    }

    /**
     * @return array<T>
     */
    public function jsonSerialize(): array
    {
        return $this->cards;
    }
}
