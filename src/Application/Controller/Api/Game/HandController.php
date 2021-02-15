<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Game;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Infrastructure\Security\PlayerStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class HandController
{
    private PlayerStorage $playerStorage;

    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    private SerializerInterface $serializer;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(PlayerStorage $playerStorage, AggregateRootRepository $gameRepository, SerializerInterface $serializer)
    {
        $this->playerStorage = $playerStorage;
        $this->gameRepository = $gameRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(string $game): Response
    {
        $player = $this->playerStorage->current();

        /** @var Game $retrieved */
        $retrieved = $this->gameRepository->retrieve(Id::fromString($game));

        $data = $this->serializer->serialize($retrieved->hand($player->id()), 'json', [
            'groups' => 'default',
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
