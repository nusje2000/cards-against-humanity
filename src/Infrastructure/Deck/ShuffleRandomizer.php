<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Deck;

use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\Deck;

final class ShuffleRandomizer implements DeckRandomizer
{
    /**
     * @inheritDoc
     */
    public function randomize(Deck $deck): Deck
    {
        $cards = $deck->cards();
        shuffle($cards);

        return ArrayDeck::fromArray($cards);
    }
}
