<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Player;

interface PlayerRepository
{
    public function retreive(Id $id): Player;
}
