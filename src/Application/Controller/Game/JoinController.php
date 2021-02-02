<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Game;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Command\Player\JoinGame;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JoinController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $id): Response
    {
        $playerId = PlayerId::fromUuid(Uuid::uuid4());

        $this->commandBus->handle(new JoinGame(Id::fromString($id), Player::create(
            $playerId,
            Username::fromString('bob'))
        ));

        return new JsonResponse([
            'id' => $playerId->toString(),
        ]);
    }
}
