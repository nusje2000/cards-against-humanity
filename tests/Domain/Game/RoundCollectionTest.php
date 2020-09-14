<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\RoundCollection;
use Nusje2000\CAH\Domain\Game\RoundInterface;
use PHPStan\Testing\TestCase;

final class RoundCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(RoundInterface::class),
            $this->createStub(RoundInterface::class),
            $this->createStub(RoundInterface::class),
        ];

        $collection = new RoundCollection($items);

        self::assertSame($collection->toArray(), $items);
    }
}
