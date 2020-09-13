<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Game;

use Nusje2000\CAH\Domain\Card\AnswerCollection;
use Nusje2000\CAH\Domain\Card\QuestionCollection;

interface GameInterface
{
    public function getCurrentRound(): RoundInterface;

    public function getPreviousRounds(): RoundCollection;

    public function getPlayers(): PlayerCollection;

    public function getQuestionDeck(): QuestionCollection;

    public function getAnswerDeck(): AnswerCollection;
}
