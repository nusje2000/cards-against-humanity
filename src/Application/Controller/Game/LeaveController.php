<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Game;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Infrastructure\Command\Player\LeaveGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class LeaveController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $gameId, string $playerId): Response
    {
        $game = GameId::fromString($gameId);
        $player = PlayerId::fromString($playerId);

        $this->commandBus->handle(new LeaveGame($game, $player));

        return new JsonResponse();
    }
}
