<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Command\Player;

use Nusje2000\CAH\Domain\Game\Id as GameId;

/**
 * @codeCoverageIgnore
 */
final class RestockAll
{
    /**
     * @var GameId
     */
    private GameId $game;

    public function __construct(GameId $game)
    {
        $this->game = $game;
    }

    public function game(): GameId
    {
        return $this->game;
    }
}
