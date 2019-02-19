<?php

namespace App\EventListener;

use App\Repository\TagRepository;
use Twig\Environment;


class CloudTagListener
{
    private $tagRepository;
    private $twig;
    public function __construct(TagRepository $tagRepository, Environment $twig)
    {
        $this->tagRepository = $tagRepository;
        $this->twig = $twig;
    }

    public function OnKernelController()
    {
        // Récupération via custum repository
        $tags = $this->tagRepository->findAll();

        // On transmet notre Cloud à twig en global
        $this->twig->addGlobal('tags', $tags );
    }
}