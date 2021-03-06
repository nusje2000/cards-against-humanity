<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Command\Player;

use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

/**
 * @codeCoverageIgnore
 */
final class LeaveGame
{
    private GameId $game;

    private PlayerId $player;

    public function __construct(GameId $game, PlayerId $player)
    {
        $this->game = $game;
        $this->player = $player;
    }

    public function game(): GameId
    {
        return $this->game;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }
}
