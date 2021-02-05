<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use JsonSerializable;
use Nusje2000\CAH\Domain\Exception\Card\EmptyDeck;
use Nusje2000\CAH\Domain\Exception\Card\NoCardFound;

/**
 * @template T of Card
 *
 * @implements Deck<T>
 */
final class ArrayDeck implements Deck, JsonSerializable
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
    public function random(): Card
    {
        /** @var string|null $card */
        $card = array_rand($this->cards);

        if (null !== $card) {
            return $this->get(Id::fromString($card));
        }

        throw EmptyDeck::create();
    }

    public function remove(Id $card): void
    {
        unset($this->cards[$card->toString()]);
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
