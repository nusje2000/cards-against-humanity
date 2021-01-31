<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Round;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Infrastructure\Command\Round\Submit;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SubmissionController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $game, string $player, string $card): Response
    {
        $this->commandBus->handle(new Submit(Id::fromString($game), PlayerId::fromString($player), CardId::fromString($card)));

        return new JsonResponse([]);
    }
}
