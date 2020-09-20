<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Lobby;

use Nusje2000\CAH\Infrastructure\Repository\LobbyRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ListController
{
    private LobbyRepositoryInterface $lobbyRepository;

    public function __construct(LobbyRepositoryInterface $lobbyRepository)
    {
        $this->lobbyRepository = $lobbyRepository;
    }

    public function __invoke(): Response
    {
        return new JsonResponse(
            $this->lobbyRepository->all(0, 25)->toArray(),
        );
    }
}
