<?php

namespace App\EventListener;

use App\Repository\TagRepository;
use Twig\Environment;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;


class CloudTagListener
{
    private $tagRepository;
    private $twig;
    private $tag;

    public function __construct(TagRepository $tagRepository, Environment $twig)
    {
        $this->tagRepository = $tagRepository;
        $this->twig = $twig;
    }

    public function OnKernelController(FilterControllerEvent $event)
    {
        // Récupération du contrôleur qui va être appelé, depuis $event
        $controller = $event->getController()[0];

        // Récupération via custum repository
        $tags = $this->tagRepository->findAll();

        // On transmet notre Cloud à twig en global
        $this->twig->addGlobal('tags', $tags );
    }
}