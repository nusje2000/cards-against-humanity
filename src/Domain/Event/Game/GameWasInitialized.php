<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Game;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Game\Id;
use UnexpectedValueException;

final class GameWasInitialized implements SerializablePayload
{
    private Id $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'game_id' => $this->id()->toString(),
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): static
    {
        if (!is_string($payload['game_id'])) {
            throw new UnexpectedValueException(
                sprintf('Exported game_id to be a string, %s received.', gettype($payload['game_id']))
            );
        }

        return new self(
            Id::fromString($payload['game_id'])
        );
    }
}
