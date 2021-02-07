<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\Id;
use PHPStan\Testing\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Nusje2000\CAH\Domain\Game\Id
 */
final class IdTest extends TestCase
{
    public function testFromString(): void
    {
        $id = Id::fromString('game');
        self::assertSame('game', $id->toString());
    }

    public function testFromUuid(): void
    {
        $uuid = Uuid::uuid4();
        $id = Id::fromUuid($uuid);
        self::assertSame($uuid->toString(), $id->toString());
    }
}
