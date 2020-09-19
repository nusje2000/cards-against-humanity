<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\Answer;
use PHPUnit\Framework\TestCase;

final class AnswerTest extends TestCase
{
    public function testAnswer(): void
    {
        $answer = new Answer('some-text');
        self::assertSame('some-text', $answer->getText());
    }
}
