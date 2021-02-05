<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Round;

use LogicException;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Exception\Round\NoSubmissionFound;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Submission;

final class Round
{
    private Id $id;

    private PlayerId $cardCzar;

    private BlackCard $blackCard;

    private ?Submission $winner = null;

    /**
     * @var array<string, Submission>
     */
    private array $submissions = [];

    public function __construct(Id $id, PlayerId $cardCzar, BlackCard $blackCard)
    {
        $this->id = $id;
        $this->cardCzar = $cardCzar;
        $this->blackCard = $blackCard;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function cardCzar(): PlayerId
    {
        return $this->cardCzar;
    }

    public function blackCard(): BlackCard
    {
        return $this->blackCard;
    }

    public function winner(): Submission
    {
        if (null === $this->winner) {
            throw new LogicException('Round is not completed yet, so no winner is present.');
        }

        return $this->winner;
    }

    /**
     * @return array<string, Submission>
     */
    public function submissions(): array
    {
        return $this->submissions;
    }

    public function playerHasSubmitted(PlayerId $id): bool
    {
        return isset($this->submissions[$id->toString()]);
    }

    public function submissionByPlayer(PlayerId $id): Submission
    {
        $submission = $this->submissions[$id->toString()] ?? null;
        if (null === $submission) {
            throw NoSubmissionFound::byPlayer($id);
        }

        return $submission;
    }

    public function submit(Submission $submission): void
    {
        $this->submissions[$submission->player()->toString()] = $submission;
    }

    public function end(Submission $winner): void
    {
        $this->winner = $winner;
    }
}
