<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

/**
 * @template T of Card
 */
interface DiscardPile
{
    /**
     * @param T $card
     */
    public function discard(Card $card): void;

    /**
     * @return array<T>
     */
    public function cards(): array;

    public function size(): int;
}
