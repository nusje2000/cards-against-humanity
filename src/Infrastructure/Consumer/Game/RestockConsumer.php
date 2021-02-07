<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Consumer\Game;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use League\Tactician\CommandBus;
use Nusje2000\CAH\Domain\Command\Player\Restock;
use Nusje2000\CAH\Domain\Command\Player\RestockAll;
use Nusje2000\CAH\Domain\Event\Player\PlayerHasJoined;
use Nusje2000\CAH\Domain\Event\Round\RoundWasCompleted;
use Nusje2000\CAH\Domain\Game\Id as GameId;

final class RestockConsumer implements Consumer
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Message $message): void
    {
        $game = $message->aggregateRootId();
        $event = $message->event();

        if ($game instanceof GameId && $event instanceof PlayerHasJoined) {
            $this->commandBus->handle(new Restock($game, $event->player()));
        }

        if ($game instanceof GameId && $event instanceof RoundWasCompleted) {
            $this->commandBus->handle(new RestockAll($game));
        }
    }
}
