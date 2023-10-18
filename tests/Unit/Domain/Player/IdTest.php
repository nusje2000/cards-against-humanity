<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Player;

use Nusje2000\CAH\Domain\Player\Id;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Nusje2000\CAH\Domain\Player\Id
 */
final class IdTest extends TestCase
{
    public function testFromString(): void
    {
        $id = Id::fromString('player');
        self::assertSame('player', $id->toString());
    }

    public function testFromUuid(): void
    {
        $uuid = Uuid::uuid4();
        $id = Id::fromUuid($uuid);
        self::assertSame($uuid->toString(), $id->toString());
    }
}
