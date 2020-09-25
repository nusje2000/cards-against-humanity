<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use JsonSerializable;

final class Question implements QuestionInterface, JsonSerializable
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'text' => $this->getText(),
        ];
    }
}
