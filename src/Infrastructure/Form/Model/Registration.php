<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Form\Model;

use Nusje2000\CAH\Domain\Player\Id;
use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Command\User\Registration as RegistrationCommand;
use Ramsey\Uuid\Uuid;
use UnexpectedValueException;

final class Registration
{
    private ?string $username = null;

    private ?string $password = null;

    public function username(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function command(): RegistrationCommand
    {
        $username = $this->username;
        if (null === $username) {
            throw new UnexpectedValueException('Expected username to be set.');
        }

        $password = $this->password;
        if (null === $password) {
            throw new UnexpectedValueException('Expected password to be set.');
        }

        return new RegistrationCommand(Id::fromUuid(Uuid::uuid4()), Username::fromString($username), $password);
    }
}
