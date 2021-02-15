<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Id;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class FetchController
{
    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    private SerializerInterface $serializer;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(AggregateRootRepository $gameRepository, SerializerInterface $serializer)
    {
        $this->gameRepository = $gameRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(string $game): Response
    {
        $data = $this->serializer->serialize($this->gameRepository->retrieve(Id::fromString($game)), 'json', [
            'groups' => 'default',
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
