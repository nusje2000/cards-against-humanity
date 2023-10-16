<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\Username;

final class UserPlayerRepository implements PlayerRepository
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function retrieve(Id $id): Player
    {
        $user = $this->userRepository->retrieve($id);

        return new Player($user->getUserIdentifier(), Username::fromString($user->getUsername()));
    }
}
