<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Domain\Game;

use Nusje2000\CAH\Domain\Game\SubmissionCollection;
use Nusje2000\CAH\Domain\Game\SubmissionInterface;
use PHPStan\Testing\TestCase;

final class SubmissionCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $items = [
            $this->createStub(SubmissionInterface::class),
            $this->createStub(SubmissionInterface::class),
            $this->createStub(SubmissionInterface::class),
        ];

        $collection = new SubmissionCollection($items);

        self::assertSame($collection->toArray(), $items);
    }

    public function testJsonSerialize(): void
    {
        $items = [
            $this->createStub(SubmissionInterface::class),
            $this->createStub(SubmissionInterface::class),
            $this->createStub(SubmissionInterface::class),
        ];

        $collection = new SubmissionCollection($items);

        self::assertSame($collection->jsonSerialize(), $items);
    }
}
