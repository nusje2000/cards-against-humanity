<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Faker\Generator;
use Nusje2000\CAH\Domain\Game\Player;
use Nusje2000\CAH\Domain\Game\PlayerCollection;
use Nusje2000\CAH\Domain\Lobby;
use Nusje2000\CAH\Domain\LobbyCollection;
use Nusje2000\CAH\Domain\LobbyInterface;

final class MockLobbyRepository implements LobbyRepositoryInterface
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function all(?int $offset = null, ?int $limit = null): LobbyCollection
    {
        $lobbies = new LobbyCollection();
        for ($i = 0; $i < $limit; $i++) {
            $lobbies->append($this->generateLobby());
        }

        return $lobbies;
    }

    private function generateLobby(): LobbyInterface
    {
        $players = new PlayerCollection();
        for ($i = 0; $i < $this->generator->numberBetween(0, 10); $i++) {
            $players->append(new Player($this->generator->name));
        }

        return new Lobby(sprintf('%s\'s game', $this->generator->firstName), $players);
    }
}
