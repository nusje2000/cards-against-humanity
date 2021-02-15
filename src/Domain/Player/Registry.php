<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use Nusje2000\CAH\Domain\Exception\Game\NoPlayersFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;

final class Registry
{
    /**
     * @var array<string, Id>
     */
    private array $players = [];

    public function join(Id $player): void
    {
        if (isset($this->players[$player->toString()])) {

        }
        $this->players[$player->toString()] = $player;
    }

    public function isJoined(Id $player): bool
    {
        return isset($this->players[$player->toString()]);
    }

    public function leave(Id $player): void
    {
        unset($this->players[$player->toString()]);
    }

    public function first(): Id
    {
        $player = reset($this->players);
        if (false === $player) {
            throw NoPlayersFound::create();
        }

        return $player;
    }

    public function next(Id $current): Id
    {
        reset($this->players);

        while (false !== ($player = current($this->players))) {
            if (!$player->isEqualTo($current)) {
                next($this->players);

                continue;
            }

            $next = next($this->players);

            return $next ?: $this->first();
        }

        throw PlayerDoesNotExist::withId($current);
    }

    /**
     * @return list<Id>
     */
    public function toArray(): array
    {
        return array_values($this->players);
    }
}
