<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Round;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Command\Round\Submit;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Infrastructure\Security\PlayerStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SubmissionController
{
    private CommandBus $commandBus;

    private PlayerStorage $playerStorage;

    public function __construct(CommandBus $commandBus, PlayerStorage $playerStorage)
    {
        $this->commandBus = $commandBus;
        $this->playerStorage = $playerStorage;
    }

    public function __invoke(string $game, string $card): Response
    {
        $this->commandBus->handle(new Submit(Id::fromString($game), $this->playerStorage->current()->id(), CardId::fromString($card)));

        return new JsonResponse([]);
    }
}
