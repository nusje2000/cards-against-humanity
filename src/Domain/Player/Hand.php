<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Exception\Card\NoCardFound;

final class Hand
{
    /**
     * @var array<string, WhiteCard>
     */
    private array $hand;

    /**
     * @param array<string, WhiteCard> $hand
     */
    private function __construct(array $hand)
    {
        $this->hand = $hand;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param array<WhiteCard> $hand
     */
    public static function fromArray(array $hand): self
    {
        $mappedHand = [];
        foreach ($hand as $card) {
            $mappedHand[$card->id()->toString()] = $card;
        }

        return new self($mappedHand);
    }

    public function add(WhiteCard $card): void
    {
        $this->hand[$card->id()->toString()] = $card;
    }

    public function remove(WhiteCard $card): void
    {
        $id = $card->id();

        if (!isset($this->hand[$id->toString()])) {
            throw NoCardFound::byId($id);
        }

        unset($this->hand[$id->toString()]);
    }

    /**
     * @return array<WhiteCard>
     */
    public function contents(): array
    {
        return $this->hand;
    }

    public function card(CardId $id): WhiteCard
    {
        if (!isset($this->hand[$id->toString()])) {
            throw NoCardFound::byId($id);
        }

        return $this->hand[$id->toString()];
    }

    public function size(): int
    {
        return count($this->hand);
    }
}
