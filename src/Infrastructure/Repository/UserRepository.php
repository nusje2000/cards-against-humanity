<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Entity\User;

interface UserRepository
{
    public function byUsername(Username $username): User;

    public function retrieve(Id $id): User;

    public function persist(User $user): void;
}
