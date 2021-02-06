<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Round;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id as GameId;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use Nusje2000\CAH\Domain\Command\Round\Start;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StartController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $game): Response
    {
        $round = RoundId::fromUuid(Uuid::uuid4());

        $this->commandBus->handle(new Start(GameId::fromString($game), $round));

        return new JsonResponse([
            'round' => $round->toString(),
        ]);
    }
}
