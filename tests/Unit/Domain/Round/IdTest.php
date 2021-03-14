<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Unit\Domain\Round;

use Nusje2000\CAH\Domain\Round\Id;
use PHPStan\Testing\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Nusje2000\CAH\Domain\Round\Id
 */
final class IdTest extends TestCase
{
    public function testFromString(): void
    {
        $id = Id::fromString('round');
        self::assertSame('round', $id->toString());
    }

    public function testFromUuid(): void
    {
        $uuid = Uuid::uuid4();
        $id = Id::fromUuid($uuid);
        self::assertSame($uuid->toString(), $id->toString());
    }
}
