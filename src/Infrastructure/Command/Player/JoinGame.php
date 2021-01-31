<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Player;

use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Player;

final class JoinGame
{
    /**
     * @var GameId
     */
    private GameId $game;

    /**
     * @var Player
     */
    private Player $player;

    public function __construct(GameId $game, Player $player)
    {
        $this->game = $game;
        $this->player = $player;
    }

    public function game(): GameId
    {
        return $this->game;
    }

    public function player(): Player
    {
        return $this->player;
    }
}
