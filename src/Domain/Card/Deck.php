<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

/**
 * @template T of Card
 */
interface Deck
{
    /**
     * @return T
     */
    public function get(Id $card): Card;

    /**
     * @return T
     */
    public function random(): Card;

    /**
     * @param Id $card
     */
    public function remove(Id $card): void;

    /**
     * @return array<T>
     */
    public function cards(): array;

    public function size(): int;
}
