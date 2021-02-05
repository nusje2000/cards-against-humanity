<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Player;

use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class JoinGame
{
    /**
     * @var GameId
     */
    private GameId $game;

    /**
     * @var PlayerId
     */
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
