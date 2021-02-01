<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Game;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Game\Rules;

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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            Rules::custom(
                $payload['hand_size'],
                $payload['max_rounds']
            )
        );
    }
}
