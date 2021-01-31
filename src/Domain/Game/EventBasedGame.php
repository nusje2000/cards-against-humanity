<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Game\GameWasInitialized;
use Nusje2000\CAH\Domain\Event\Player\PlayerJoined;
use Nusje2000\CAH\Domain\Event\Player\PlayerLeft;
use Nusje2000\CAH\Domain\Event\Round\RoundWasCompleted;
use Nusje2000\CAH\Domain\Event\Round\RoundWasStarted;
use Nusje2000\CAH\Domain\Exception\Game\NoActiveRound;
use Nusje2000\CAH\Domain\Exception\Game\NoPlayersFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Round;
use Nusje2000\CAH\Domain\RoundId;
use Nusje2000\CAH\Domain\Submission;
use Nusje2000\CAH\Domain\Table;
use Ramsey\Uuid\Uuid;

final class EventBasedGame implements Game, AggregateRoot
{
    use AggregateRootBehaviour;

    private Table $table;

    private ?Round $currentRound = null;

    /**
     * @var array<string, Player>
     */
    private array $players = [];

    /**
     * @param Table $table
     */
    public static function initialize(Id $id, Table $table): self
    {
        $game = new self($id);
        $game->recordThat(new GameWasInitialized($table));

        return $game;
    }

    public function start(): void
    {
        $this->startRound();
    }

    /**
     * @inheritDoc
     */
    public function players(): array
    {
        return $this->players;
    }

    public function join(Player $player): void
    {
        $this->recordThat(new PlayerJoined($player));
    }

    public function leave(Player $player): void
    {
        $this->recordThat(new PlayerLeft($player->id()));
    }

    public function table(): Table
    {
        return $this->table;
    }

    public function currentRound(): Round
    {
        if (null === $this->currentRound) {
            throw NoActiveRound::create();
        }

        return $this->currentRound;
    }

    public function startRound(): void
    {
        $newCardCzar = next($this->players);

        if (false === $newCardCzar) {
            throw NoPlayersFound::create();
        }

        $newBlackCard = $this->table()->blackDeck()->draw();

        $this->recordThat(new RoundWasStarted($newCardCzar->id(), $newBlackCard));
    }

    public function completeRound(Submission $winner): void
    {
        $this->recordThat(new RoundWasCompleted($winner->player()->id(), $winner->card()));
    }

    public function end(): void
    {
    }

    public function submit(Player $player, WhiteCard $card): void
    {
        $this->currentRound()->submit(new Submission($player, $card));
    }

    public function applyGameWasInitialized(GameWasInitialized $event): void
    {
        $this->table = $event->table();
    }

    public function applyPlayerJoined(PlayerJoined $event): void
    {
        $this->players[$event->id()->toString()] = $event->player();
    }

    public function applyPlayerLeft(PlayerLeft $event): void
    {
        unset($this->players[$event->id()->toString()]);
    }

    public function applyRoundWasStarted(RoundWasStarted $event): void
    {
        $this->currentRound = new Round(RoundId::fromUuid(Uuid::uuid4()), $this->playerById($event->cardCzar()), $event->blackCard());
    }

    public function applyRoundWasCompleted(RoundWasCompleted $event): void
    {
        $this->currentRound()->end(
            new Submission($this->playerById($event->winningPlayer()), $event->winningCard())
        );

        $this->table()->blackDiscardPile()->discard(
            $this->currentRound()->blackCard()
        );

        foreach ($this->currentRound()->submissions() as $submission) {
            $submission->player()->hand()->add(
                $this->table()->whiteDeck()->draw()
            );

            $this->table()->whiteDiscardPile()->discard($submission->card());
        }
    }

    private function playerById(PlayerId $id): Player
    {
        $player = $this->players()[$id->toString()] ?? null;
        if (null === $player) {
            throw PlayerDoesNotExist::withId($id);
        }

        return $player;
    }
}
