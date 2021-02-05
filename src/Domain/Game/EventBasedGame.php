<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Card\Deck;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Event\Deck\BlackCardWasDiscarded;
use Nusje2000\CAH\Domain\Event\Deck\WhiteCardWasDiscarded;
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
use Nusje2000\CAH\Domain\Exception\Round\SubmissionAlreadyPresent;
use Nusje2000\CAH\Domain\Player\Hand;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\PlayerRegistry;
use Nusje2000\CAH\Domain\Round\Id as RoundId;
use Nusje2000\CAH\Domain\Round\Round;
use Nusje2000\CAH\Domain\Round\RoundRegistry;
use Nusje2000\CAH\Domain\Submission;
use Nusje2000\CAH\Domain\Table;

final class EventBasedGame implements Game, AggregateRoot
{
    use AggregateRootBehaviour;

    private ?Table $table = null;

    private ?Rules $rules = null;

    private RoundRegistry $rounds;

    private PlayerRegistry $players;

    /**
     * @var array<string, Hand>
     */
    private array $hands = [];

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

    /**
     * @inheritDoc
     */
    public function players(): array
    {
        return $this->players->toArray();
    }

    public function hand(PlayerId $player): Hand
    {
        $hand = $this->hands[$player->toString()] ?? null;
        if (null === $hand) {
            throw PlayerDoesNotExist::withId($player);
        }

        return $hand;
    }

    public function join(PlayerId $player): void
    {
        $this->recordThat(new PlayerJoined($player));
        // TODO: move this to a consumer
        $this->restockPlayer($player);
    }

    public function leave(PlayerId $player): void
    {
        $this->recordThat(new PlayerLeft($player));
    }

    public function draw(PlayerId $id): void
    {
        $this->recordThat(new PlayerHasDrawnCard($id, $this->table()->whiteDeck()->random()));
    }

    public function rounds(): RoundRegistry
    {
        return $this->rounds;
    }

    public function startRound(RoundId $roundId): void
    {
        if (!$this->canStartNewRound()) {
            throw RoundLimitReached::create();
        }

        $cardCzar = $this->players->first();

        $previousRound = $this->rounds->previous();
        if (null !== $previousRound) {
            $cardCzar = $this->players->next($previousRound->cardCzar());
        }

        $this->recordThat(new RoundWasStarted($roundId, $cardCzar, $this->table()->blackDeck()->random()));
    }

    public function submit(PlayerId $player, CardId $card): void
    {
        $this->recordThat(new CardWasSubmitted($player, $card));
    }

    public function completeRound(PlayerId $winner): void
    {
        $this->discardPlayedCards();
        $this->recordThat(new RoundWasCompleted($winner));
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
        $this->hands[$event->player()->toString()] = Hand::empty();
        $this->players->join($event->player());
    }

    public function applyPlayerLeft(PlayerLeft $event): void
    {
        $this->players->leave($event->player());
        unset($this->hands[$event->player()->toString()]);
    }

    public function applyPlayerHasDrawnCard(PlayerHasDrawnCard $event): void
    {
        $deck = $this->table()->whiteDeck();

        $this->hand($event->player())->add($event->card());
        $deck->remove($event->card()->id());
    }

    public function applyWhiteCardWasDiscarded(WhiteCardWasDiscarded $event): void
    {
        $this->table()->whiteDiscardPile()->discard($event->card());
    }

    public function applyBlackCardWasDiscarded(BlackCardWasDiscarded $event): void
    {
        $this->table()->blackDiscardPile()->discard($event->card());
    }

    public function applyRoundWasStarted(RoundWasStarted $event): void
    {
        $this->rounds->start(new Round($event->id(), $event->cardCzar(), $event->card()));
    }

    public function applyCardWasSubmitted(CardWasSubmitted $event): void
    {
        $player = $event->player();
        if (!$this->players->isJoined($player)) {
            throw PlayerDoesNotExist::withId($player);
        }

        if ($this->rounds->current()->playerHasSubmitted($player)) {
            throw SubmissionAlreadyPresent::forPlayer($player);
        }

        $hand = $this->hand($player);
        $card = $hand->card($event->card());

        $this->rounds->current()->submit(
            new Submission($player, $card)
        );

        $hand->remove($card);
    }

    public function applyRoundWasCompleted(RoundWasCompleted $event): void
    {
        $winner = $this->rounds->current()->submissions()[$event->winningPlayer()->toString()];
        $this->rounds->current()->end($winner);

        $this->rounds->finishCurrentRound();
    }

    private function discardPlayedCards(): void
    {
        $current = $this->rounds->current();

        $this->recordThat(new BlackCardWasDiscarded($current->blackCard()));

        foreach ($current->submissions() as $submission) {
            $this->recordThat(new WhiteCardWasDiscarded($submission->card()));
        }
    }

    private function restockPlayer(PlayerId $player): void
    {
        while ($this->rules()->handSize() > $this->hand($player)->size()) {
            $this->draw($player);
        }
    }

    private function canStartNewRound(): bool
    {
        return null === $this->rules()->maxRounds() || count($this->rounds->completed()) < $this->rules()->maxRounds();
    }
}
