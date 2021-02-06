<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Command\Game\Create;
use Nusje2000\CAH\Domain\Game\EventBasedGame;

final class CreateHandler
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

    public function handle(Create $create): void
    {
        $game = EventBasedGame::initialize($create->id(), $create->rules(), $create->whiteDeck(), $create->blackDeck());
        $this->gameRepository->persist($game);
    }
}
