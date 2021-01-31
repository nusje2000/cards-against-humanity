<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use Nusje2000\CAH\Domain\Round\RoundRegistry;
use Nusje2000\CAH\Domain\Table;

interface Game
{
    public function id(): Id;

    public function table(): Table;

    public function rules(): Rules;

    /**
     * @return array<Player>
     */
    public function players(): array;

    public function join(Player $player): void;

    public function leave(PlayerId $player): void;

    public function startRound(RoundId $roundId): void;

    public function rounds(): RoundRegistry;

    public function completeRound(PlayerId $winner): void;

    public function submit(PlayerId $player, CardId $card): void;
}
