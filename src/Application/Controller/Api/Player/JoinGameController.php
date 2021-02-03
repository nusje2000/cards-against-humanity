<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Player;

use Faker\Factory;
use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Command\Player\JoinGame;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JoinGameController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $game): Response
    {
        $playerId = PlayerId::fromUuid(Uuid::uuid4());

        $this->commandBus->handle(new JoinGame(Id::fromString($game), Player::create(
            $playerId,
            Username::fromString(Factory::create()->firstName))
        ));

        return new JsonResponse([
            'id' => $playerId->toString(),
        ]);
    }
}
