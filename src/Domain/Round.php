<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain;

use LogicException;
use Nusje2000\CAH\Domain\Card\BlackCard;
use Nusje2000\CAH\Domain\Player\Player;

final class Round
{
    private Player $cardCzar;

    private BlackCard $blackCard;

    private ?Submission $winner = null;

    /**
     * @var array<Submission>
     */
    private array $submissions = [];

    /**
     * @var RoundId
     */
    private RoundId $id;

    public function __construct(RoundId $id, Player $cardCzar, BlackCard $blackCard)
    {
        $this->id = $id;
        $this->cardCzar = $cardCzar;
        $this->blackCard = $blackCard;
    }

    public function id(): RoundId
    {
        return $this->id;
    }

    public function cardCzar(): Player
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
     * @return array<Submission>
     */
    public function submissions(): array
    {
        return $this->submissions;
    }

    public function submit(Submission $submission): void
    {
        $this->submissions[] = $submission;
    }

    public function end(Submission $winner): void
    {
        $this->winner = $winner;
    }
}
