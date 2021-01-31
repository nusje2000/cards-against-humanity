<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use JsonSerializable;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use UnexpectedValueException;

final class Hand implements JsonSerializable
{
    /**
     * @var array<WhiteCard>
     */
    private array $hand;

    /**
     * @param array<WhiteCard> $hand
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
        return new self($hand);
    }

    public function add(WhiteCard $card): void
    {
        $this->hand[] = $card;
    }

    public function remove(WhiteCard $card): void
    {
        $index = array_search($card, $this->hand, true);
        if (false === $index) {
            throw new UnexpectedValueException('Card is not in players hand.');
        }

        unset($this->hand[$index]);
    }

    /**
     * @return array<WhiteCard>
     */
    public function contents(): array
    {
        return $this->hand;
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'contents' => $this->contents(),
        ];
    }
}
