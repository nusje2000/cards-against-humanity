<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Id;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class VersionController
{
    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(AggregateRootRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(string $game): Response
    {
        /** @var EventBasedGame $retrieved */
        $retrieved = $this->gameRepository->retrieve(Id::fromString($game));

        return new JsonResponse([
            'version' => $retrieved->aggregateRootVersion(),
        ]);
    }
}
