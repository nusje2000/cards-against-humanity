<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Subscriber;

use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class HttpErrorMappingSubscriber implements EventSubscriberInterface
{
    private const MAPPING = [
        PlayerDoesNotExist::class => Response::HTTP_NOT_FOUND,
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'mapException',
        ];
    }

    public function mapException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        /**
         * @var class-string<Throwable> $class
         * @var int                     $status
         */
        foreach (self::MAPPING as $class => $status) {
            if (get_class($exception) === $class) {
                $exception = new HttpException($status, $exception->getMessage(), $exception);
            }
        }

        $event->setThrowable($exception);
    }
}
