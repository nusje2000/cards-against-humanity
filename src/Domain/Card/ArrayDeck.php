<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use Nusje2000\CAH\Domain\Exception\Card\EmptyDeck;
use Nusje2000\CAH\Domain\Exception\Card\NoCardFound;

/**
 * @template T of Card
 *
 * @implements Deck<T>
 */
final class ArrayDeck implements Deck
{
    /**
     * @var array<string, T> $cards
     */
    private array $cards;

    /**
     * @param array<string, T> $cards
     */
    private function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    /**
     * @template CardType of Card
     *
     * @psalm-param array<CardType> $cards
     *
     * @param array<Card>           $cards
     *
     * @return self<CardType>
     */
    public static function fromArray(array $cards): self
    {
        $mapped = [];
        foreach ($cards as $card) {
            $mapped[$card->id()->toString()] = $card;
        }

        return new self($mapped);
    }

    /**
     * @return T
     */
    public function get(Id $card): Card
    {
        if (isset($this->cards[$card->toString()])) {
            return $this->cards[$card->toString()];
        }

        throw NoCardFound::byId($card);
    }

    /**
     * @return T
     */
    public function first(): Card
    {
        $card = reset($this->cards);

        if (false !== $card) {
            return $card;
        }

        throw EmptyDeck::create();
    }

    public function remove(Id $card): void
    {
        unset($this->cards[$card->toString()]);
    }

    public function cards(): array
    {
        return array_values($this->cards);
    }

    public function size(): int
    {
        return count($this->cards);
    }
}
