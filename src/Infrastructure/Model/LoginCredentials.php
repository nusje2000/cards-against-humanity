<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Model;

use Nusje2000\CAH\Domain\Player\Username;
use Symfony\Component\HttpFoundation\Request;
use UnexpectedValueException;

final class LoginCredentials
{
    private Username $username;

    private string $password;

    private string $csrfToken;

    private function __construct(Username $username, string $password, string $csrfToken)
    {
        $this->username = $username;
        $this->password = $password;
        $this->csrfToken = $csrfToken;
    }

    public static function fromRequest(Request $request): self
    {
        /** @var mixed $username */
        $username = $request->request->get('username');
        if (!is_string($username)) {
            throw new UnexpectedValueException('Expected username credential to be a string.');
        }

        /** @var mixed $password */
        $password = $request->request->get('password');
        if (!is_string($password)) {
            throw new UnexpectedValueException('Expected password to be a string.');
        }

        /** @var mixed $csrfToken */
        $csrfToken = $request->request->get('_csrf_token');
        if (!is_string($csrfToken)) {
            throw new UnexpectedValueException('Expected csrf_token to be a string.');
        }

        return new self(Username::fromString($username), $password, $csrfToken);
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function csrfToken(): string
    {
        return $this->csrfToken;
    }
}
