<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use Faker\Factory;
use Faker\Generator;
use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Card\ArrayDeck;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Command\Game\Create;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Game\Rules;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateController
{
    private CommandBus $commandBus;

    private Generator $faker;

    public function __construct(CommandBus $commandBus)
    {
        $this->faker = Factory::create();
        $this->commandBus = $commandBus;
    }

    public function __invoke(): Response
    {
        $id = Id::fromUuid(Uuid::uuid4());

        $this->commandBus->handle(new Create($id, Rules::default(), $this->whiteDeck(), $this->blackDeck()));

        return new JsonResponse([
            'id' => $id->toString(),
        ]);
    }

    /**
     * @return Deck<WhiteCard>
     */
    private function whiteDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 200) as $index) {
            $cards[] = new WhiteCard(CardId::fromUuid(Uuid::uuid4()), Text::fromString(implode(' ', (array) $this->faker->words(10))));
        }

        return ArrayDeck::fromArray($cards);
    }

    /**
     * @return Deck<BlackCard>
     */
    private function blackDeck(): Deck
    {
        $cards = [];
        foreach (range(1, 50) as $index) {
            $cards[] = new BlackCard(CardId::fromUuid(Uuid::uuid4()), Text::fromString(implode(' ', (array) $this->faker->words(10))));
        }

        return ArrayDeck::fromArray($cards);
    }
}
