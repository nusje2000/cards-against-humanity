<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\User;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;

final class Registration
{
    private Id $id;

    private Username $username;

    private string $password;

    public function __construct(Id $id, Username $username, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }
}
