<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\LobbyCollection;

interface LobbyRepositoryInterface
{
    public function all(?int $offset = null, ?int $limit = null): LobbyCollection;
}
