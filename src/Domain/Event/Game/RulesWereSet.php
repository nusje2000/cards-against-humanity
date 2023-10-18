<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Game;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Game\Rules;
use UnexpectedValueException;

final class RulesWereSet implements SerializablePayload
{
    private Rules $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    public function rules(): Rules
    {
        return $this->rules;
    }

    /**
     * @return array<mixed>
     */
    public function toPayload(): array
    {
        return [
            'hand_size' => $this->rules()->handSize(),
            'max_rounds' => $this->rules()->maxRounds(),
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     *
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): static
    {
        if (!is_int($payload['hand_size'])) {
            throw new UnexpectedValueException(
                sprintf('Exported hand_size to be a string, %s received.', gettype($payload['hand_size']))
            );
        }

        if (!is_int($payload['max_rounds']) && $payload['max_rounds'] !== null) {
            throw new UnexpectedValueException(
                sprintf('Exported max_rounds to be a string, %s received.', gettype($payload['max_rounds']))
            );
        }

        return new self(
            Rules::custom(
                $payload['hand_size'],
                $payload['max_rounds']
            )
        );
    }
}
