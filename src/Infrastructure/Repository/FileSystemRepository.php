<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Repository;

use BadMethodCallException;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\PaginationCursor;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use UnexpectedValueException;

final class FileSystemRepository implements MessageRepository
{
    private MessageSerializer $messageSerializer;

    private string $rootDirectory;

    public function __construct(MessageSerializer $messageSerializer, string $aggregateName, string $rootDirectory)
    {
        $this->messageSerializer = $messageSerializer;
        $this->rootDirectory = $rootDirectory . '/' . $aggregateName;
    }

    public function persist(Message ...$messages): void
    {
        foreach ($messages as $message) {
            $id = $message->aggregateRootId();
            $this->verifyAggregateRootDirectoryExists($id);
            $path = $this->messageFile($message);

            file_put_contents($path, json_encode($this->messageSerializer->serializeMessage($message), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $files = $this->findFilesByAggregateRootId($id);

        $version = 0;

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            /** @var array<mixed> $decoded */
            $decoded = json_decode($file->getContents(), true, flags: JSON_THROW_ON_ERROR);
            $message = $this->messageSerializer->unserializePayload($decoded);
            $version = $message->aggregateVersion();

            yield $message;
        }

        return $version;
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $files = $this->findFilesByAggregateRootId($id);

        $version = 0;

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            /** @var array<mixed> $decoded */
            $decoded = json_decode($file->getContents(), true);
            $message = $this->messageSerializer->unserializePayload($decoded);

            if ($message->aggregateVersion() <= $aggregateRootVersion) {
                continue;
            }

            $version = $message->aggregateVersion();

            yield $message;
        }

        return $version;
    }

    public function paginate(PaginationCursor $cursor): Generator
    {
        throw new BadMethodCallException('Method has not been implemented.');
    }

    private function verifyAggregateRootDirectoryExists(?AggregateRootId $id): void
    {
        $path = $this->aggregateRootDirectory($id);
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \RuntimeException(sprintf('Cannot create directory "%s".', $path));
            };
        }
    }

    private function messageFile(Message $message): string
    {
        /** @var string $eventId */
        $eventId = $message->header(Header::EVENT_ID) ?? Uuid::uuid4()->toString();

        return sprintf('%s/v%05d_%s.json', $this->aggregateRootDirectory($message->aggregateRootId()), $message->aggregateVersion(), $eventId);
    }

    private function aggregateRootDirectory(?AggregateRootId $id): string
    {
        if (null === $id) {
            return $this->rootDirectory() . '/unknown_root';
        }

        return $this->rootDirectory() . '/root_' . $id->toString();
    }

    private function rootDirectory(): string
    {
        return $this->rootDirectory;
    }

    private function findFilesByAggregateRootId(AggregateRootId $id): Finder
    {
        if (!is_dir($this->aggregateRootDirectory($id))) {
            throw new UnexpectedValueException(sprintf('No root found by id "%s".', $id->toString()));
        }

        return Finder::create()->in($this->aggregateRootDirectory($id))->name('*.json')->sortByName();
    }
}
