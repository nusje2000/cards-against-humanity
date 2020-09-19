<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Lobby;

use Faker\Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class ListController
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function __invoke(): Response
    {
        $lobbies = [];
        for ($i = 1; $i < 25; $i++) {
            $lobbies[] = $this->generateLobby();
        }

        return new JsonResponse($lobbies);
    }

    /**
     * @return array<string, string>
     */
    private function generateLobby(): array
    {
        return [
            'id' => (string)Uuid::v4(),
            'name' => sprintf('%s\'s game', $this->generator->firstName),
        ];
    }
}
