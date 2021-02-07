<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Round;

use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Exception\Round\NoSubmissionFound;
use Nusje2000\CAH\Domain\Exception\Round\NoWinnerFound;
use Nusje2000\CAH\Domain\Exception\Round\SubmissionAlreadyPresent;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Submission;

final class Round
{
    private Id $id;

    private PlayerId $cardCzar;

    private BlackCard $blackCard;

    private ?PlayerId $winner = null;

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
            throw NoWinnerFound::create();
        }

        return $this->submissionByPlayer($this->winner);
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
        $player = $submission->player();
        if ($this->playerHasSubmitted($player)) {
            throw SubmissionAlreadyPresent::forPlayer($player);
        }

        $this->submissions[$player->toString()] = $submission;
    }

    public function end(PlayerId $winner): void
    {
        if (!$this->playerHasSubmitted($winner)) {
            throw NoSubmissionFound::byPlayer($winner);
        }

        $this->winner = $winner;
    }
}
