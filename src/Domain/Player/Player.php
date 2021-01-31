<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Player;

use JsonSerializable;

final class Player implements JsonSerializable
{
    private Id $id;

    private Username $username;

    private Hand $hand;

    public function __construct(Id $id, Username $username, Hand $hand)
    {
        $this->id = $id;
        $this->username = $username;
        $this->hand = $hand;
    }

    public static function create(Id $id, Username $username): self
    {
        return new self($id, $username, Hand::empty());
    }

    public static function createWithHand(Id $id, Username $username, Hand $hand): self
    {
        return new self($id, $username, $hand);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function hand(): Hand
    {
        return $this->hand;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->toString(),
            'username' => $this->username()->toString(),
            'hand' => $this->hand(),
        ];
    }
}
