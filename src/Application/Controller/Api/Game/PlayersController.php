<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Game;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Infrastructure\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class PlayersController
{
    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    private PlayerRepository $playerRepository;

    private SerializerInterface $serializer;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(AggregateRootRepository $gameRepository, PlayerRepository $playerRepository, SerializerInterface $serializer)
    {
        $this->gameRepository = $gameRepository;
        $this->serializer = $serializer;
        $this->playerRepository = $playerRepository;
    }

    public function __invoke(string $game): Response
    {
        /** @var Game $retrieved */
        $retrieved = $this->gameRepository->retrieve(Id::fromString($game));

        $players = array_map(function (PlayerId $id): Player {
            return $this->playerRepository->retrieve($id);
        }, $retrieved->players()->toArray());

        $data = $this->serializer->serialize($players, 'json', [
            'groups' => 'default',
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
