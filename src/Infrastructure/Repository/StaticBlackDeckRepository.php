<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Infrastructure\Deck\DeckRandomizer;
use Ramsey\Uuid\Uuid;

/**
 * @implements DeckRepository<BlackCard>
 */
final class StaticBlackDeckRepository implements DeckRepository
{
    /**
     * @var DeckRandomizer
     */
    private DeckRandomizer $randomizer;

    public function __construct(DeckRandomizer $randomizer)
    {
        $this->randomizer = $randomizer;
    }

    /**
     * @return Deck<BlackCard>
     */
    public function retrieve(): Deck
    {
        $cards = [];

        $decoded = $this->getJson();
        foreach ($decoded['black'] as $text) {
            $cards[] = new BlackCard(Id::fromUuid(Uuid::uuid4()), Text::fromString($text));
        }

        return $this->randomizer->randomize(ArrayDeck::fromArray($cards));
    }

    /**
     * @return array{white: array<string>, black: array<string>}
     */
    private function getJson(): array
    {
        /** @var string $contents */
        $contents = file_get_contents(dirname(__DIR__) . '/Resources/decks/official.json');

        /** @var array{white: array<string>, black: array<string>} $decoded */
        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
