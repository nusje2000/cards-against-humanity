<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Card;

interface Card
{
    public function id(): Id;

    public function contents(): Text;
}
