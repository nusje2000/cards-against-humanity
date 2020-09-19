<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

final class Question implements QuestionInterface
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
}
