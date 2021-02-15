<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Player;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Infrastructure\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FetchController
{
    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function __invoke(string $player): Response
    {
        $object = $this->playerRepository->retreive(Id::fromString($player));

        return new JsonResponse([
            'id' => $object->id()->toString(),
            'username' => $object->username()->toString(),
        ]);
    }
}
