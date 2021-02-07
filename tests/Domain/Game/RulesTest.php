<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\Rules;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nusje2000\CAH\Domain\Game\Rules
 */
final class RulesTest extends TestCase
{
    public function testDefault(): void
    {
        $rules = Rules::default();
        self::assertSame(7, $rules->handSize());
        self::assertSame(8, $rules->maxRounds());
    }

    public function testCustom(): void
    {
        $rules = Rules::custom(5, 10);
        self::assertSame(5, $rules->handSize());
        self::assertSame(10, $rules->maxRounds());
    }
}
