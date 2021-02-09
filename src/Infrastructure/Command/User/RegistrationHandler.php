<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Command\User;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class RegistrationHandler
{
    private const SALT_SIZE = 16;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(UserRepository $userRepository, EncoderFactoryInterface $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
    }

    public function handle(Registration $register): void
    {
        $user = $this->createUser($register->id(), $register->username(), $register->password());
        $this->userRepository->persist($user);
    }

    private function createUser(Id $id, Username $username, string $plainPassword): User
    {
        $salt = $this->createSalt();
        $password = $this->encodePassword($plainPassword, $salt);

        return new User($id, $username, $password, $salt);
    }

    private function encodePassword(string $plainPassword, string $salt): string
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);

        return $encoder->encodePassword($plainPassword, $salt);
    }

    private function createSalt(): string
    {
        return random_bytes(self::SALT_SIZE);
    }
}
