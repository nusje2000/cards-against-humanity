<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Command\Round;

use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

/**
 * @codeCoverageIgnore
 */
final class Submit
{
    /**
     * @var GameId
     */
    private GameId $game;

    /**
     * @var PlayerId
     */
    private PlayerId $player;

    /**
     * @var CardId
     */
    private CardId $card;

    public function __construct(GameId $game, PlayerId $player, CardId $card)
    {
        $this->game = $game;
        $this->player = $player;
        $this->card = $card;
    }

    public function game(): GameId
    {
        return $this->game;
    }

    public function player(): PlayerId
    {
        return $this->player;
    }

    public function card(): CardId
    {
        return $this->card;
    }
}
