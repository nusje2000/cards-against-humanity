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
    public function draw(): Card;

    /**
     * @return array<T>
     */
    public function cards(): array;

    public function size(): int;
}
