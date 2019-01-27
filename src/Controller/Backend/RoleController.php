<?php

namespace App\Controller\Backend;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/role")
 */
class RoleController extends AbstractController
{
    /**
     * @Route("/", name="backend_role_index", methods="GET")
     */
    public function index(RoleRepository $roleRepository): Response
    {
        return $this->render('backend/role/index.html.twig', ['roles' => $roleRepository->findAll()]);
    }
}
