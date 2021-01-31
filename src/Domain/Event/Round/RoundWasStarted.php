<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Round;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Round\Id as RoundId;

final class RoundWasStarted implements SerializablePayload
{
    private RoundId $id;

    public function __construct(RoundId $id)
    {
        $this->id = $id;
    }

    public function id(): RoundId
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'id' => $this->id()->toString(),
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArrayAccess
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            RoundId::fromString($payload['id']),
        );
    }
}
