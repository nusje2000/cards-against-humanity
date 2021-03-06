<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Player\Hand;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Registry as PlayerRegistry;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use Nusje2000\CAH\Domain\Round\Registry;
use Nusje2000\CAH\Domain\Table;

interface Game
{
    public function id(): Id;

    public function table(): Table;

    public function rules(): Rules;

    public function players(): PlayerRegistry;

    public function join(PlayerId $player): void;

    public function leave(PlayerId $player): void;

    public function hand(PlayerId $player): Hand;

    public function rounds(): Registry;

    public function startRound(RoundId $roundId): void;

    public function submit(PlayerId $player, CardId $card): void;

    public function completeRound(PlayerId $winner): void;
}
