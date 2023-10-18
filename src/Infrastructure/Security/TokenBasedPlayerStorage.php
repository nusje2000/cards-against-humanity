<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Security;

use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Exception\Player\NoPlayerFound;
use Nusje2000\CAH\Infrastructure\Repository\PlayerRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TokenBasedPlayerStorage implements PlayerStorage
{
    private TokenStorageInterface $tokenStorage;

    private PlayerRepository $repository;

    public function __construct(TokenStorageInterface $tokenStorage, PlayerRepository $repository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->repository = $repository;
    }

    public function current(): Player
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw NoPlayerFound::noToken();
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw NoPlayerFound::invalidUser(User::class, $user);
        }

        return $this->repository->retrieve($user->getId());
    }

    public function currentOrNull(): ?Player
    {
        try {
            return $this->current();
        } catch (NoPlayerFound $exception) {
            return null;
        }
    }
}
