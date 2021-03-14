<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Functional;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

final class AuthenticationTest extends PantherTestCase
{
    public function testRegistration(): string
    {
        $username = substr(uniqid('user_', true), 0, 15);

        $client = self::createPantherClient();
        $client->get('/register');
        $form = $client->getCrawler()->selectButton('Register')->form([
            'registration[username]' => $username,
            'registration[password]' => 'password',
        ]);

        $client->submit($form);

        self::assertSame('/login', $this->getPath($client));

        return $username;
    }

    /**
     * @depends testRegistration
     */
    public function testLogin(string $username): void
    {
        $client = self::createPantherClient();
        $client->get('/login');
        $form = $client->getCrawler()->selectButton('Login')->form([
            'username' => $username,
            'password' => 'password',
        ]);

        $client->submit($form);

        self::assertSame('/', $this->getPath($client));

        self::assertStringContainsString('LOGOUT', $client->getCrawler()->text());
    }

    private function getPath(Client $client): string
    {
        /** @var array{path: string} $parsed */
        $parsed = parse_url($client->getCurrentURL());

        return $parsed['path'];
    }
}
