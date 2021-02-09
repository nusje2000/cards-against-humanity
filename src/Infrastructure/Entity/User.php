<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Entity;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    private string $id;

    private string $username;

    private string $password;

    private string $salt;

    public function __construct(Id $id, Username $username, string $password, string $salt)
    {
        $this->id = $id->toString();
        $this->username = $username->get();
        $this->password = $password;
        $this->salt = $salt;
    }

    public function getId(): Id
    {
        return Id::fromString($this->id);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }
}
