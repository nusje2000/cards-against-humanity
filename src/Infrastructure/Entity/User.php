<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Entity;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    private string $id;

    private string $username;

    private string $password;

    public function __construct(Id $id, Username $username, string $password)
    {
        $this->id = $id->toString();
        $this->username = $username->get();
        $this->password = $password;
    }

    public function getUserIdentifier(): Id
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


    /**
     * @deprecated for upgrading Symfony 5 to 6
     */
    public function getSalt(): string
    {
        return '';
    }
}
