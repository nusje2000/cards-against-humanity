<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Round;

use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Round\Id as RoundId;

final class Start
{
    /**
     * @var Id
     */
    private Id $game;

    /**
     * @var RoundId
     */
    private RoundId $round;

    public function __construct(Id $game, RoundId $round)
    {
        $this->game = $game;
        $this->round = $round;
    }

    public function game(): Id
    {
        return $this->game;
    }

    public function round(): RoundId
    {
        return $this->round;
    }
}
