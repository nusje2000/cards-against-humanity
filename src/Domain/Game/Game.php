<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use LogicException;
use Nusje2000\CAH\Domain\Card\Deck\AnswerDeckInterface;
use Nusje2000\CAH\Domain\Card\Deck\QuestionDeckInterface;

final class Game implements GameInterface
{
    private PlayerCollection $players;
    private QuestionDeckInterface $questionDeck;
    private AnswerDeckInterface $answerDeck;
    private RoundCollection $rounds;

    public function __construct(PlayerCollection $players, QuestionDeckInterface $questionDeck, AnswerDeckInterface $answerDeck)
    {
        $this->players = $players;
        $this->questionDeck = $questionDeck;
        $this->answerDeck = $answerDeck;
        $this->rounds = new RoundCollection();
    }

    public function setNextRound(RoundInterface $round): void
    {
        $this->rounds->append($round);
    }

    public function getCurrentRound(): RoundInterface
    {
        $currentRound = $this->rounds->last();
        if (null === $currentRound) {
            throw new LogicException('No rounds set.');
        }

        return $currentRound;
    }

    public function getPreviousRounds(): RoundCollection
    {
        return new RoundCollection(array_slice($this->rounds->toArray(), 0, $this->rounds->count() - 1));
    }

    public function getPlayers(): PlayerCollection
    {
        return $this->players;
    }

    public function getQuestionDeck(): QuestionDeckInterface
    {
        return $this->questionDeck;
    }

    public function getAnswerDeck(): AnswerDeckInterface
    {
        return $this->answerDeck;
    }
}
