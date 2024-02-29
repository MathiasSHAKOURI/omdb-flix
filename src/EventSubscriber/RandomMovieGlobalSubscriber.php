<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class RandomMovieGlobalSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $movieRepository;

    public function __construct(Environment $twig, MovieRepository $movieRepository)
    {
        $this->twig = $twig;
        $this->movieRepository = $movieRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $randomMovie = $this->movieRepository->findRandomMovie();
        $this->twig->addGlobal("randomMovie",$randomMovie);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
