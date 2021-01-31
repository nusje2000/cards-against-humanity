<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use JsonSerializable;

/**
 * @template T of Card
 *
 * @implements DiscardPile<T>
 */
final class ArrayDiscardPile implements DiscardPile, JsonSerializable
{
    /**
     * @var array<T>
     */
    private array $pile;

    /**
     * @param array<T> $pile
     */
    private function __construct(array $pile)
    {
        $this->pile = $pile;
    }

    /**
     * @return self<Card>
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @template CardType of Card
     *
     * @param array<array-key, CardType> $pile
     *
     * @return self<CardType>
     */
    public static function fromArray(array $pile): self
    {
        return new self($pile);
    }

    /**
     * @param T $card
     */
    public function discard(Card $card): void
    {
        $this->pile[] = $card;
    }

    /**
     * @return array<T>
     */
    public function cards(): array
    {
        return $this->pile;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return count($this->pile);
    }

    /**
     * @return array<T>
     */
    public function jsonSerialize(): array
    {
        return $this->cards();
    }
}
