<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain;

use Nusje2000\CAH\Domain\Game\PlayerCollection;
use Nusje2000\CAH\Domain\Lobby;
use PHPUnit\Framework\TestCase;

final class LobbyTest extends TestCase
{
    public function testLobby(): void
    {
        $players = new PlayerCollection();

        $lobby = new Lobby('some-name', $players);

        self::assertSame([
            'name' => 'some-name',
            'players' => $players,
        ], $lobby->jsonSerialize());
    }
}
