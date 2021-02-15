<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Security;

use Nusje2000\CAH\Domain\Player\Player;

interface PlayerStorage
{
    public function current(): Player;

    public function currentOrNull(): ?Player;
}
