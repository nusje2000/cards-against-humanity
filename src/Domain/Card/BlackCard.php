<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

use JsonSerializable;

final class BlackCard implements Card, JsonSerializable
{
    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var Text
     */
    private Text $text;

    public function __construct(Id $id, Text $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function contents(): Text
    {
        return $this->text;
    }

    /**
     * @return array<string, mixed>\
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->toString(),
            'contents' => $this->contents()->toString(),
        ];
    }
}
