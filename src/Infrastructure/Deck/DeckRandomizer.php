<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Deck;

use Nusje2000\CAH\Domain\Card\Card;
use Nusje2000\CAH\Domain\Card\Deck;

interface DeckRandomizer
{
    /**
     * @template T of Card
     *
     * @param Deck<T> $deck
     *
     * @return Deck<T>
     */
    public function randomize(Deck $deck): Deck;
}
