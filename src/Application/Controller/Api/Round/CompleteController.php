<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Round;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Command\Round\Complete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CompleteController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $game, string $winner): Response
    {
        $this->commandBus->handle(new Complete(Id::fromString($game), PlayerId::fromString($winner)));

        return new JsonResponse([]);
    }
}
