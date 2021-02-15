<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Player;

use Nusje2000\CAH\Domain\Exception\Game\NoPlayersFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Registry;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Player\Registry
 */
final class RegistryTest extends TestCase
{
    public function testJoin(): void
    {
        $subject = $this->createSubject();
        self::assertFalse($subject->isJoined(Id::fromString('player-4')));
        $subject->join(Id::fromString('player-4'));
        self::assertTrue($subject->isJoined(Id::fromString('player-4')));
    }

    public function testIsJoined(): void
    {
        $subject = $this->createSubject();
        self::assertTrue($subject->isJoined(Id::fromString('player-1')));
        self::assertTrue($subject->isJoined(Id::fromString('player-2')));
        self::assertTrue($subject->isJoined(Id::fromString('player-3')));
        self::assertFalse($subject->isJoined(Id::fromString('player-4')));
    }

    public function testLeave(): void
    {
        $subject = $this->createSubject();
        self::assertTrue($subject->isJoined(Id::fromString('player-3')));
        $subject->leave(Id::fromString('player-3'));
        self::assertFalse($subject->isJoined(Id::fromString('player-3')));
    }

    public function testFirst(): void
    {
        $subject = $this->createSubject();
        self::assertEquals(Id::fromString('player-1'), $subject->first());

        $this->expectExceptionObject(NoPlayersFound::create());
        (new Registry())->first();
    }

    public function testNext(): void
    {
        $subject = $this->createSubject();
        self::assertEquals(Id::fromString('player-2'), $subject->next(Id::fromString('player-1')));
        self::assertEquals(Id::fromString('player-3'), $subject->next(Id::fromString('player-2')));
        self::assertEquals(Id::fromString('player-1'), $subject->next(Id::fromString('player-3')));

        $this->expectExceptionObject(PlayerDoesNotExist::withId(Id::fromString('player-4')));
        $subject->next(Id::fromString('player-4'));
    }

    public function testToArray(): void
    {
        $subject = $this->createSubject();
        self::assertEquals([
            0 => Id::fromString('player-1'),
            1 => Id::fromString('player-2'),
            2 => Id::fromString('player-3'),
        ], $subject->toArray());
    }

    private function createSubject(): Registry
    {
        $registry = new Registry();
        $registry->join(Id::fromString('player-1'));
        $registry->join(Id::fromString('player-2'));
        $registry->join(Id::fromString('player-3'));

        return $registry;
    }
}
