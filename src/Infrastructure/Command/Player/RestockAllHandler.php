<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Player;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Command\Player\RestockAll;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Game;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;

final class RestockAllHandler
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

    public function handle(RestockAll $restock): void
    {
        /** @var EventBasedGame $game */
        $game = $this->gameRepository->retrieve($restock->game());

        foreach ($game->players() as $player) {
            $this->restockPlayer($game, $player);
        }

        $this->gameRepository->persist($game);
    }

    private function restockPlayer(EventBasedGame $game, PlayerId $player): void
    {
        for ($left = $this->requiredDrawAmount($game, $player); $left > 0; $left--) {
            $game->draw($player);
        }
    }

    private function requiredDrawAmount(Game $game, PlayerId $player): int
    {
        $required = $game->rules()->handSize() - $game->hand($player)->size();

        return $required > 0 ? $required : 0;
    }
}
