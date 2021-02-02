<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Game;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Infrastructure\Command\Game\Start;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StartController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $id): Response
    {
        $this->commandBus->handle(new Start(Id::fromString($id)));

        return new JsonResponse();
    }
}
