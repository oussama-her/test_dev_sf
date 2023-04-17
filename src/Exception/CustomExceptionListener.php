<?php

namespace App\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CustomExceptionListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new Response();

        if (
            $exception instanceof TransportExceptionInterface
            || $exception instanceof ServerExceptionInterface
        ) {
            $this->logger->error($exception->getMessage());
            $response->setContent('Server Error');
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);
        }

        if ($exception instanceof ClientExceptionInterface) {
            $this->logger->error($exception->getMessage());
            $response->setContent('Client Error');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }

        if ($exception instanceof RedirectionExceptionInterface) {
            $this->logger->error($exception->getMessage());
            $response->setContent('Redirection');
            $response->setStatusCode(Response::HTTP_PERMANENTLY_REDIRECT);
            $event->setResponse($response);
        }
    }


    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
