<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

final class BlackCard implements Card
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
}
