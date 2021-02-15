<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Client\Game;

use EventSauce\EventSourcing\AggregateRootRepository;
use Nusje2000\CAH\Domain\Game\EventBasedGame;
use Nusje2000\CAH\Domain\Game\Id;
use Nusje2000\CAH\Infrastructure\Security\PlayerStorage;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ViewController
{
    /**
     * @var AggregateRootRepository<EventBasedGame>
     */
    private AggregateRootRepository $gameRepository;

    private Environment $twig;

    private PlayerStorage $playerStorage;

    /**
     * @param AggregateRootRepository<EventBasedGame> $gameRepository
     */
    public function __construct(AggregateRootRepository $gameRepository, Environment $twig, PlayerStorage $playerStorage)
    {
        $this->gameRepository = $gameRepository;
        $this->twig = $twig;
        $this->playerStorage = $playerStorage;
    }

    public function __invoke(string $game): Response
    {
        return new Response(
            $this->twig->render('view.html.twig', [
                'game' => $this->gameRepository->retrieve(
                    Id::fromString($game)
                ),
                'current_player' => $this->playerStorage->current(),
            ])
        );
    }
}
