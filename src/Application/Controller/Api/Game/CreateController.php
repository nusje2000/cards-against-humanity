<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Api\Game;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Command\Game\Create;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Domain\Game\Rules;
use Nusje2000\CAH\Infrastructure\Repository\DeckRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateController
{
    private CommandBus $commandBus;

    /**
     * @var DeckRepository<WhiteCard>
     */
    private DeckRepository $whiteDeckRepository;

    /**
     * @var DeckRepository<BlackCard>
     */
    private DeckRepository $blackDeckRepository;

    /**
     * @param DeckRepository<WhiteCard> $whiteDeckRepository
     * @param DeckRepository<BlackCard> $blackDeckRepository
     */
    public function __construct(CommandBus $commandBus, DeckRepository $whiteDeckRepository, DeckRepository $blackDeckRepository)
    {
        $this->commandBus = $commandBus;
        $this->whiteDeckRepository = $whiteDeckRepository;
        $this->blackDeckRepository = $blackDeckRepository;
    }

    public function __invoke(): Response
    {
        $id = Id::fromUuid(Uuid::uuid4());

        $this->commandBus->handle(new Create($id, Rules::default(), $this->whiteDeckRepository->retrieve(), $this->blackDeckRepository->retrieve()));

        return new JsonResponse([
            'id' => $id->toString(),
        ]);
    }
}
