<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\Game;

use Nusje2000\CAH\Domain\Game\Id;

final class Start
{
    /**
     * @var Id
     */
    private Id $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function id(): Id
    {
        return $this->id;
    }
}
