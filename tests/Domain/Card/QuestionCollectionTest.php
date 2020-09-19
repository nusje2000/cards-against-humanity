<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Card;

use Nusje2000\CAH\Domain\Card\QuestionCollection;
use Nusje2000\CAH\Domain\Card\QuestionInterface;
use PHPStan\Testing\TestCase;

final class QuestionCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(QuestionInterface::class),
            $this->createStub(QuestionInterface::class),
            $this->createStub(QuestionInterface::class),
        ];

        $collection = new QuestionCollection($items);

        self::assertSame($collection->toArray(), $items);
    }
}
