<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Player;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Command\Player\LeaveGame;
use Nusje2000\CAH\Domain\Game\EventBasedGame;

final class LeaveGameHandler
{
    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(AggregateRootRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function handle(LeaveGame $join): void
    {
        /** @var EventBasedGame $game */
        $game = $this->gameRepository->retrieve($join->game());
        $game->leave($join->player());
        $this->gameRepository->persist($game);
    }
}
