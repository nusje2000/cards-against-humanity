<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

final class Player
{
    private Id $id;

    private Username $username;

    public function __construct(Id $id, Username $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }
}
