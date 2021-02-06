<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Command\Round;

use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class Complete
{
    /**
     * @var GameId
     */
    private GameId $game;

    /**
     * @var PlayerId
     */
    private PlayerId $winner;

    public function __construct(GameId $game, PlayerId $winner)
    {
        $this->game = $game;
        $this->winner = $winner;
    }

    public function game(): GameId
    {
        return $this->game;
    }

    public function winner(): PlayerId
    {
        return $this->winner;
    }
}
