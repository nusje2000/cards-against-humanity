<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Round;
use Nusje2000\CAH\Domain\Submission;
use Nusje2000\CAH\Domain\Table;

interface Game
{
    public function start(): void;

    /**
     * @return array<Player>
     */
    public function players(): array;

    public function join(Player $player): void;

    public function leave(Player $player): void;

    public function table(): Table;

    public function currentRound(): Round;

    public function completeRound(Submission $winner): void;

    public function startRound(): void;

    public function end(): void;

    public function submit(Player $player, WhiteCard $card): void;
}
