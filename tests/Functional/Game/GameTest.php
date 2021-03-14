<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Tests\Functional\Game;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

final class GameTest extends PantherTestCase
{
    private const CARD_XPATH = '//*[contains(concat(" ", normalize-space(@class)," "), " card ") and contains(string(), "%s")]';

    public function testJoin(): string
    {
        $player1 = $this->createLoggedInClient('player_1');

        $player1->clickLink('Start Game');
        $path = $this->getPath($player1);
        self::assertStringContainsString('/game/', $path);
        $this->assertPlayerList($player1, []);

        $player2 = $this->createLoggedInClient('player_2');
        $player2->get($path);
        $this->assertPlayerList($player2, []);

        $this->clickCard($player1, 'Join Game');
        $this->assertPlayerList($player1, ['player_1']);
        $this->assertPlayerList($player2, ['player_1']);

        $this->clickCard($player2, 'Join Game');
        $this->assertPlayerList($player1, ['player_1', 'player_2']);
        $this->assertPlayerList($player2, ['player_1', 'player_2']);

        $this->clickCard($player1, 'Start round');

        $this->clickCard($player2, '500 hours of court-ordered community service.');
        $this->clickCard($player1, '500 hours of court-ordered community service.');

        $this->clickCard($player2, 'Start round');

        $this->clickCard($player1, '#fearvokallchicks.');
        $this->clickCard($player2, '#fearvokallchicks.');

        return $path;
    }

    private function createLoggedInClient(string $user): Client
    {
        $client = self::createAdditionalPantherClient();

        $client->get('/login');
        $form = $client->getCrawler()->selectButton('Login')->form([
            'username' => $user,
            'password' => $user,
        ]);

        $client->submit($form);

        sleep(1);

        return $client;
    }

    private function getCard(Client $client, string $contents): Crawler
    {
        $cards = null;

        $client->wait(10)->until(function () use ($contents, $client, &$cards) {
            $cards = $client->refreshCrawler()->filterXPath(sprintf(self::CARD_XPATH, $contents));

            return $cards->count() > 0;
        });

        /** @var Crawler|null $cards */
        self::assertNotNull($cards);

        return $cards;
    }

    private function clickCard(Client $client, string $contents): void
    {
        $client->wait(10)->until(function () use ($contents, $client) {
            return false === stripos($this->getCard($client, $contents)->attr('class') ?? '', 'card__disabled');
        });

        $this->getCard($client, $contents)->click();
    }

    /**
     * @param list<string> $expected
     */
    private function assertPlayerList(Client $client, array $expected): void
    {
        $client->wait(10)->until(function () use ($expected, $client) {
            $card = $this->getCard($client, 'Players:');
            $players = $card->filter('.w-full')->extract(['_text']);

            return $players === $expected;
        });
    }

    private function getPath(Client $client): string
    {
        /** @var array{path: string} $parsed */
        $parsed = parse_url($client->getCurrentURL());

        return $parsed['path'];
    }
}
