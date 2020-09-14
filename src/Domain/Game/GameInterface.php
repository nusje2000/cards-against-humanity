<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\Deck\AnswerDeckInterface;
use Nusje2000\CAH\Domain\Card\Deck\QuestionDeckInterface;

interface GameInterface
{
    public function getCurrentRound(): RoundInterface;

    public function getPreviousRounds(): RoundCollection;

    public function getPlayers(): PlayerCollection;

    public function getQuestionDeck(): QuestionDeckInterface;

    public function getAnswerDeck(): AnswerDeckInterface;
}
