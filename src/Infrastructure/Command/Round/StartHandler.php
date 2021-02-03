<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Round;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;

final class StartHandler
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

    public function handle(Start $start): void
    {
        /** @var EventBasedGame $game */
        $game = $this->gameRepository->retrieve($start->game());
        $game->startRound($start->round());
        $this->gameRepository->persist($game);
    }
}
