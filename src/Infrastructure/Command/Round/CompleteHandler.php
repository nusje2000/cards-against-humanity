<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Round;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;

final class CompleteHandler
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

    public function handle(Complete $complete): void
    {
        /** @var EventBasedGame $game */
        $game = $this->gameRepository->retrieve($complete->game());
        $game->completeRound($complete->winner());
        $this->gameRepository->persist($game);
    }
}
