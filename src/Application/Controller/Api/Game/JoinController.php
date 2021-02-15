<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Command\Player\JoinGame;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Infrastructure\Security\PlayerStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JoinController
{
    private PlayerStorage $playerStorage;

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus, PlayerStorage $playerStorage)
    {
        $this->commandBus = $commandBus;
        $this->playerStorage = $playerStorage;
    }

    public function __invoke(string $game): Response
    {
        $player = $this->playerStorage->current();
        $this->commandBus->handle(new JoinGame(Id::fromString($game), $player->id()));

        return new JsonResponse([
            'id' => $player->id()->toString(),
        ]);
    }
}
