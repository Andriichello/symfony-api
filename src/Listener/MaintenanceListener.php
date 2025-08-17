<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class MaintenanceListener
{
    #[AsEventListener(event: 'kernel.request')]
    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // get current second
        $second = (int) date('s');
        $segment = (int) ($second / 10);

        if ($segment % 2 === 1) {
            throw new ServiceUnavailableHttpException(
                10 - $second % 10,
                'The application is currently under maintenance.'
            );
        }
    }
}
