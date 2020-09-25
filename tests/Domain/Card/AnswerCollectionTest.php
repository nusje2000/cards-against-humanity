<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\AnswerCollection;
use Nusje2000\CAH\Domain\Card\AnswerInterface;
use PHPStan\Testing\TestCase;

final class AnswerCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(AnswerInterface::class),
            $this->createStub(AnswerInterface::class),
            $this->createStub(AnswerInterface::class),
        ];

        $collection = new AnswerCollection($items);

        self::assertSame($collection->toArray(), $items);
    }

    public function testJsonSerialize(): void
    {
        $items = [
            $this->createStub(AnswerInterface::class),
            $this->createStub(AnswerInterface::class),
            $this->createStub(AnswerInterface::class),
        ];

        $collection = new AnswerCollection($items);

        self::assertSame($collection->jsonSerialize(), $items);
    }
}
