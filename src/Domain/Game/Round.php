<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use LogicException;
use Nusje2000\CAH\Domain\Card\QuestionInterface;

final class Round implements RoundInterface
{
    private PlayerInterface $cardCzar;
    private QuestionInterface $question;
    private SubmissionCollection $submissions;
    private SubmissionInterface $winner;

    public function __construct(PlayerInterface $cardCzar, QuestionInterface $question)
    {
        $this->cardCzar = $cardCzar;
        $this->question = $question;
        $this->submissions = new SubmissionCollection();
    }

    public function getCardCzar(): PlayerInterface
    {
        return $this->cardCzar;
    }

    public function getQuestion(): QuestionInterface
    {
        return $this->question;
    }

    public function getSubmissions(): SubmissionCollection
    {
        return $this->submissions;
    }

    public function getWinner(): SubmissionInterface
    {
        if (null === $this->winner) {
            throw new LogicException('No winner was selected yet.');
        }

        return $this->winner;
    }

    public function setWinner(SubmissionInterface $winner): void
    {
        $this->winner = $winner;
    }

    public function isCompleted(): bool
    {
        return null !== $this->winner;
    }
}
