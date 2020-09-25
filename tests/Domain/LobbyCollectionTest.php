<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain;

use Nusje2000\CAH\Domain\LobbyCollection;
use Nusje2000\CAH\Domain\LobbyInterface;
use PHPStan\Testing\TestCase;

final class LobbyCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(LobbyInterface::class),
            $this->createStub(LobbyInterface::class),
            $this->createStub(LobbyInterface::class),
        ];

        $collection = new LobbyCollection($items);

        self::assertSame($collection->toArray(), $items);
    }

    public function testJsonSerialize(): void
    {
        $items = [
            $this->createStub(LobbyInterface::class),
            $this->createStub(LobbyInterface::class),
            $this->createStub(LobbyInterface::class),
        ];

        $collection = new LobbyCollection($items);

        self::assertSame($collection->jsonSerialize(), $items);
    }
}
