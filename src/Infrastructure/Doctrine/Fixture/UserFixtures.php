<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Doctrine\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class UserFixtures implements FixtureInterface
{
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);

        foreach ($this->users() as $user) {
            $user = new User(
                Id::fromString('id_' . $user['username']->toString()),
                $user['username'],
                $passwordHasher->hash($user['password']),
                );

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array<array{username: Username, password: string}>
     */
    private function users(): array
    {
        return [
            ['username' => Username::fromString('player_1'), 'password' => 'player_1'],
            ['username' => Username::fromString('player_2'), 'password' => 'player_2'],
            ['username' => Username::fromString('player_3'), 'password' => 'player_3'],
            ['username' => Username::fromString('player_4'), 'password' => 'player_4'],
            ['username' => Username::fromString('player_5'), 'password' => 'player_5'],
        ];
    }
}
