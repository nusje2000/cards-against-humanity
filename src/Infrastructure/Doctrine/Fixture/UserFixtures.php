<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Doctrine\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class UserFixtures implements FixtureInterface
{
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);

        foreach ($this->users() as $user) {
            $user = new User(
                Id::fromString('id_' . $user['username']->toString()),
                $user['username'],
                $encoder->encodePassword($user['password'], $user['salt']),
                $user['salt']
            );

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array<array{username: Username, password: string, salt: string}>
     */
    private function users(): array
    {
        return [
            ['username' => Username::fromString('player_1'), 'password' => 'player_1', 'salt' => 'salt_1'],
            ['username' => Username::fromString('player_2'), 'password' => 'player_2', 'salt' => 'salt_2'],
            ['username' => Username::fromString('player_3'), 'password' => 'player_3', 'salt' => 'salt_3'],
            ['username' => Username::fromString('player_4'), 'password' => 'player_4', 'salt' => 'salt_4'],
            ['username' => Username::fromString('player_5'), 'password' => 'player_5', 'salt' => 'salt_5'],
        ];
    }
}
