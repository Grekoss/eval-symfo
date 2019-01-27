<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ViewStaticController extends AbstractController
{
    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu()
    {
        return $this->render('cgu.html.twig');
    }
}
