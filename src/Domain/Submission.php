<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain;

use JsonSerializable;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class Submission implements JsonSerializable
{
    private PlayerId $player;

    private WhiteCard $card;

    public function __construct(PlayerId $player, WhiteCard $card)
    {
        $this->player = $player;
        $this->card = $card;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }

    public function card(): WhiteCard
    {
        return $this->card;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'player' => $this->player(),
            'card' => $this->card(),
        ];
    }
}
