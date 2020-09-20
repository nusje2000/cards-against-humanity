<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain;

use Nusje2000\CAH\Domain\Game\PlayerCollection;

interface LobbyInterface
{
    public function getName(): string;

    public function getPlayers(): PlayerCollection;
}
