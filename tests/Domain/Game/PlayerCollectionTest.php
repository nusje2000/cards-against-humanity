<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\PlayerCollection;
use Nusje2000\CAH\Domain\Game\PlayerInterface;
use PHPStan\Testing\TestCase;

final class PlayerCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
            $this->createStub(PlayerInterface::class),
        ];

        $collection = new PlayerCollection($items);

        self::assertSame($collection->toArray(), $items);
    }
}
