<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Game\GameWasInitialized;
use Nusje2000\CAH\Domain\Event\Game\RulesWereSet;
use Nusje2000\CAH\Domain\Event\Game\TableWasCreated;
use Nusje2000\CAH\Domain\Event\Player\PlayerHasDrawnCard;
use Nusje2000\CAH\Domain\Event\Player\PlayerJoined;
use Nusje2000\CAH\Domain\Event\Player\PlayerLeft;
use Nusje2000\CAH\Domain\Event\Round\CardWasSubmitted;
use Nusje2000\CAH\Domain\Event\Round\RoundWasCompleted;
use Nusje2000\CAH\Domain\Event\Round\RoundWasStarted;
use Nusje2000\CAH\Domain\Exception\Game\NoRulesFound;
use Nusje2000\CAH\Domain\Exception\Game\NoTableFound;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Domain\Exception\Game\RoundLimitReached;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\PlayerRegistry;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use Nusje2000\CAH\Domain\Round\Round;
use Nusje2000\CAH\Domain\Round\RoundRegistry;
use Nusje2000\CAH\Domain\Submission;
use Nusje2000\CAH\Domain\Table;
use Ramsey\Uuid\Uuid;

final class EventBasedGame implements Game, AggregateRoot
{
    use AggregateRootBehaviour;

    private ?Table $table = null;

    private ?Rules $rules = null;

    private RoundRegistry $rounds;

    private PlayerRegistry $players;

    public function __construct(Id $id)
    {
        $this->aggregateRootId = $id;
        $this->players = new PlayerRegistry();
        $this->rounds = new RoundRegistry();
    }

    /**
     * @param Deck<WhiteCard> $whiteDeck
     * @param Deck<BlackCard> $blackDeck
     */
    public static function initialize(Id $id, Rules $rules, Deck $whiteDeck, Deck $blackDeck): self
    {
        $game = new self($id);
        $game->recordThat(new GameWasInitialized($id));
        $game->recordThat(new RulesWereSet($rules));
        $game->recordThat(new TableWasCreated($whiteDeck, $blackDeck));

        return $game;
    }

    public function id(): Id
    {
        /** @var Id $id */
        $id = $this->aggregateRootId();

        return $id;
    }

    public function table(): Table
    {
        if (null === $this->table) {
            throw NoTableFound::create();
        }

        return $this->table;
    }

    public function rules(): Rules
    {
        if (null === $this->rules) {
            throw NoRulesFound::create();
        }

        return $this->rules;
    }

    public function rounds(): RoundRegistry
    {
        return $this->rounds;
    }

    /**
     * @inheritDoc
     */
    public function players(): array
    {
        return $this->players->toArray();
    }

    public function join(Player $player): void
    {
        $this->recordThat(new PlayerJoined($player));
    }

    public function leave(Player $player): void
    {
        $this->recordThat(new PlayerLeft($player->id()));
    }

    public function start(): void
    {
        $this->restockPlayers();
        $this->startRound();
    }

    public function startRound(): void
    {
        if (!$this->canStartNewRound()) {
            throw RoundLimitReached::create();
        }

        $this->recordThat(new RoundWasStarted(
            RoundId::fromUuid(Uuid::uuid4()),
            $this->players->rotate()->id(),
            $this->table()->blackDeck()->draw()
        ));
    }

    public function submit(PlayerId $player, CardId $card): void
    {
        $this->recordThat(new CardWasSubmitted($player, $card));
    }

    public function completeRound(PlayerId $winner): void
    {
        $this->recordThat(new RoundWasCompleted($winner));
        $this->restockPlayers();
    }

    public function applyGameWasInitialized(GameWasInitialized $event): void
    {
        $this->aggregateRootId = $event->id();
    }

    public function applyTableWasCreated(TableWasCreated $event): void
    {
        $this->table = Table::create($event->whiteDeck(), $event->blackDeck());
    }

    public function applyRulesWereSet(RulesWereSet $event): void
    {
        $this->rules = $event->rules();
    }

    public function applyPlayerJoined(PlayerJoined $event): void
    {
        $this->players->join($event->player());
    }

    public function applyPlayerLeft(PlayerLeft $event): void
    {
        $this->players->leave($event->id());
    }

    public function applyPlayerHasDrawnCard(PlayerHasDrawnCard $event): void
    {
        $this->players->findById($event->player())->hand()->add($event->card());
    }

    public function applyRoundWasStarted(RoundWasStarted $event): void
    {
        $this->rounds->start(
            new Round(
                $event->id(),
                $this->playerById($event->cardCzar()),
                $event->blackCard()
            )
        );
    }

    public function applyCardWasSubmitted(CardWasSubmitted $event): void
    {
        $player = $this->players->findById($event->player());

        $this->rounds->current()->submit(
            new Submission(
                $player,
                $player->hand()->cardById($event->card()),
            )
        );
    }

    public function applyRoundWasCompleted(RoundWasCompleted $event): void
    {
        $winner = $this->rounds->current()->submissions()[$event->winningPlayer()->toString()];
        $this->rounds->current()->end($winner);

        // Remove this from RoundWasCompleted event
        $this->discardPlayedCards();
        $this->rounds->finishCurrentRound();
    }

    private function discardPlayedCards(): void
    {
        $current = $this->rounds()->current();

        $this->table()->blackDiscardPile()->discard(
            $current->blackCard()
        );

        foreach ($current->submissions() as $submission) {
            $submission->player()->hand()->remove($submission->card());
            $this->table()->whiteDiscardPile()->discard($submission->card());
        }
    }

    private function restockPlayers(): void
    {
        foreach ($this->players() as $player) {
            $this->restockPlayer($player);
        }
    }

    private function restockPlayer(Player $player): void
    {
        while ($this->rules()->handSize() > $player->hand()->size()) {
            $this->recordThat(new PlayerHasDrawnCard($player->id(), $this->table()->whiteDeck()->draw()));
        }
    }

    private function canStartNewRound(): bool
    {
        return null === $this->rules()->maxRounds() || count($this->rounds->completed()) < $this->rules()->maxRounds();
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
