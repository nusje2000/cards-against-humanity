<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Command\Game;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Game\Rules;

final class Create
{
    private Id $id;

    private Rules $rules;

    /**
     * @var Deck<WhiteCard>
     */
    private Deck $whiteDeck;

    /**
     * @var Deck<BlackCard>
     */
    private Deck $blackDeck;

    /**
     * @param Deck<WhiteCard> $whiteDeck
     * @param Deck<BlackCard> $blackDeck
     */
    public function __construct(Id $id, Rules $rules, Deck $whiteDeck, Deck $blackDeck)
    {
        $this->id = $id;
        $this->rules = $rules;
        $this->whiteDeck = $whiteDeck;
        $this->blackDeck = $blackDeck;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function rules(): Rules
    {
        return $this->rules;
    }

    /**
     * @return Deck<WhiteCard>
     */
    public function whiteDeck(): Deck
    {
        return $this->whiteDeck;
    }

    /**
     * @return Deck<BlackCard>
     */
    public function blackDeck(): Deck
    {
        return $this->blackDeck;
    }
}
