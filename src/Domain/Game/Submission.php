<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use JsonSerializable;
use Nusje2000\CAH\Domain\Card\AnswerInterface;

final class Submission implements SubmissionInterface, JsonSerializable
{
    private PlayerInterface $player;
    private AnswerInterface $answer;

    public function __construct(PlayerInterface $player, AnswerInterface $answer)
    {
        $this->player = $player;
        $this->answer = $answer;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getAnswer(): AnswerInterface
    {
        return $this->answer;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'player' => $this->getPlayer(),
            'answer' => $this->getAnswer(),
        ];
    }
}
