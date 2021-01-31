<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Nusje2000\CAH\Domain\Exception\Game\NoPlayersFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;

final class PlayerRegistry
{
    /**
     * @var array<string, Player>
     */
    private array $players = [];

    private bool $hasRotated = false;

    public function findById(Id $id): Player
    {
        $player = $this->players[$id->toString()] ?? null;
        if (null === $player) {
            throw PlayerDoesNotExist::withId($id);
        }

        return $player;
    }

    public function join(Player $player): void
    {
        $this->players[$player->id()->toString()] = $player;
    }

    public function leave(Id $player): void
    {
        unset($this->players[$player->toString()]);
    }

    public function rotate(): Player
    {
        $player = current($this->players);
        if (false === $player) {
            $player = reset($this->players);
        }

        if (false === $player) {
            throw NoPlayersFound::create();
        }

        next($this->players);

        return $player;
    }

    /**
     * @return array<string, Player>
     */
    public function toArray(): array
    {
        return $this->players;
    }
}
