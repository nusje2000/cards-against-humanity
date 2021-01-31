<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Domain\Event\Player;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Nusje2000\CAH\Domain\Card\Id as CardId;
use Nusje2000\CAH\Domain\Card\Text;
use Nusje2000\CAH\Domain\Card\WhiteCard;
use Nusje2000\CAH\Domain\Player\Hand;
use Nusje2000\CAH\Domain\Player\Id as PlayerId;
use Nusje2000\CAH\Domain\Player\Player;
use Nusje2000\CAH\Domain\Player\Username;

final class PlayerJoined implements SerializablePayload
{
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function id(): PlayerId
    {
        return $this->player->id();
    }

    public function player(): Player
    {
        return $this->player;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        $player = $this->player();

        return [
            'player' => [
                'id' => $player->id()->toString(),
                'username' => $player->username()->toString(),
                'hand' => array_map(static function (WhiteCard $card): array {
                    return [
                        'id' => $card->id()->toString(),
                        'contents' => $card->contents()->toString(),
                    ];
                }, $player->hand()->contents()),
            ],
        ];
    }

    /**
     * @param array<mixed> $payload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            new Player(
                PlayerId::fromString($payload['player']['id']),
                Username::fromString($payload['player']['username']),
                Hand::fromArray(array_map(static function (array $card): WhiteCard {
                    return new WhiteCard(CardId::fromString($card['id']), Text::fromString($card['contents']));
                }, $payload['player']['hand']['contents']))
            )
        );
    }
}
