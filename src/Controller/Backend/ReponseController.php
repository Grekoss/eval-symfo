<?php

namespace App\Controller\Backend;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/reponse")
 */
class ReponseController extends Controller
{
    /**
     * @Route("/", name="backend_reponse_index", methods="GET")
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('backend/reponse/index.html.twig', ['reponses' => $reponseRepository->findAllReponseByRecentDateAll()]);
    }

    /**
     * @Route("/new", name="backend_reponse_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();

            return $this->redirectToRoute('backend_reponse_index');
        }

        return $this->render('backend/reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_reponse_show", methods="GET")
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('backend/reponse/show.html.twig', ['reponse' => $reponse]);
    }

    /**
     * @Route("/{id}/edit", name="backend_reponse_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_reponse_edit', ['id' => $reponse->getId()]);
        }

        return $this->render('backend/reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_reponse_delete", methods="DELETE")
     */
    public function delete(Request $request, Reponse $reponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reponse);
            $em->flush();
        }

        return $this->redirectToRoute('backend_reponse_index');
    }

    /**
     * @Route("/{id}/active", name="backend_reponse_active")
     */
    public function active(Reponse $reponse, ReponseRepository $reponseRepository) : Response
    {
        $status = $reponse->getIsActive();

        if ($status == true) {
            $reponse->setIsActive(false);
        }
        if ($status == false) {
            $reponse->setIsActive(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($reponse);
        $em->flush();

        return $this->render('backend/reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAllReponseByRecentDateAll()
        ]); 
    }
}
