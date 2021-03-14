<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Deck;

use Nusje2000\CAH\Domain\Card\Deck;

final class StaticRandomizer implements DeckRandomizer
{
    public function randomize(Deck $deck): Deck
    {
        return $deck;
    }
}
