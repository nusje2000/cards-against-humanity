<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Entity\User;

final class DoctrineUserRepository implements UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function byUsername(Username $username): User
    {
        $repository = $this->entityManager->getRepository(User::class);

        $user = $repository->findOneBy([
            'username' => $username,
        ]);

        if (null === $user) {
            throw PlayerDoesNotExist::withUsername($username);
        }

        return $user;
    }

    public function retrieve(Id $id): User
    {
        $user = $this->entityManager->find(User::class, $id->toString());
        if (null === $user) {
            throw PlayerDoesNotExist::withId($id);
        }

        return $user;
    }

    public function persist(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
