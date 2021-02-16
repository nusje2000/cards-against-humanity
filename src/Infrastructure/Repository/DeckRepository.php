<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\Card\Card;
use Nusje2000\CAH\Domain\Card\Deck;

/**
 * @template T of Card
 */
interface DeckRepository
{
    /**
     * @psalm-return Deck<T>
     *
     * @return Deck<Card>
     */
    public function retrieve(): Deck;
}
