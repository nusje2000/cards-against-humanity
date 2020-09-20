<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Model;

use JsonSerializable;
use Nusje2000\CAH\Domain\Game\PlayerCollection;
use Nusje2000\CAH\Domain\LobbyInterface;

final class Lobby implements LobbyInterface, JsonSerializable
{
    private string $name;
    private PlayerCollection $players;

    public function __construct(string $name, PlayerCollection $players)
    {
        $this->name = $name;
        $this->players = $players;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayers(): PlayerCollection
    {
        return $this->players;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'player_count' => $this->getPlayers()->count(),
        ];
    }
}
