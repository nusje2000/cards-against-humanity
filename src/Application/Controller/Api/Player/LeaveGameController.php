<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Player;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Infrastructure\Command\Player\LeaveGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class LeaveGameController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $game, string $player): Response
    {
        $this->commandBus->handle(new LeaveGame(GameId::fromString($game), PlayerId::fromString($player)));

        return new JsonResponse();
    }
}
