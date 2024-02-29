<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $maintenance;
    private $parameterBag;

    public function __construct(string $maintenance, ParameterBagInterface $parameterBag)
    {
        $this->maintenance = $maintenance;
        $this->parameterBag = $parameterBag;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        // if(!$this->parameterBag->get("app.maintenance")) return;
        // $message = $this->parameterBag->get("app.maintenance");

        if ($this->maintenance) {
            $response = $event->getResponse();
            $response
                ->setContent(str_replace('<body>', '<body><div class="alert alert-danger">' . $this->maintenance . '</div>', $response->getContent()));
                // ->send();
                // $event->setResponse(new Response($response));
        };
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
