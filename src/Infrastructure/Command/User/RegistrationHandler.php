<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\User;

use Exception;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class RegistrationHandler
{
    private const SALT_SIZE = 16;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var PasswordHasherFactoryInterface
     */
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(UserRepository $userRepository, PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    /**
     * @throws Exception
     */
    public function handle(Registration $register): void
    {
        $salt = $this->createSalt();
        $saltedPassword = $register->password() . $salt;
        $password = $this->hashPassword($saltedPassword);

        $user = new User($register->id(), $register->username(), $password, $salt);
        $this->userRepository->persist($user);
    }

    private function hashPassword(string $plainPassword): string
    {
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);

        return $passwordHasher->hash($plainPassword);
    }

    /**
     * @throws Exception
     */
    private function createSalt(): string
    {
        return random_bytes(self::SALT_SIZE);
    }
}
