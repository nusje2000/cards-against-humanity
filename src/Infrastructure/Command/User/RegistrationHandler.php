<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\User;

use Exception;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class RegistrationHandler
{
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
        $user = new User($register->id(), $register->username(), $this->hashPassword($register->password()));
        $this->userRepository->persist($user);
    }

    private function hashPassword(string $plainPassword): string
    {
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);

        return $passwordHasher->hash($plainPassword);
    }
}
