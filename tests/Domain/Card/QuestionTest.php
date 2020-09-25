<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\Question;
use PHPUnit\Framework\TestCase;

final class QuestionTest extends TestCase
{
    public function testAnswer(): void
    {
        $question = new Question('some-text');
        self::assertSame('some-text', $question->getText());
    }

    public function testJsonSerialize(): void
    {
        $question = new Question('some-text');

        self::assertSame($question->jsonSerialize(), [
            'text' => 'some-text',
        ]);
    }
}
