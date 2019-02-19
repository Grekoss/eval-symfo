<?php

namespace App\Controller\Backend;

use App\Entity\Reponse;
use App\Form\ReponseType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="backend_reponse_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_question_listReponse', ['id' => $reponse->getQuestion()->getId()]);
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

        return $this->redirectToRoute('backend_question_listReponse', ['id' => $reponse->getQuestion()->getId()
        ]);
    }

    /**
     * @Route("/{id}/active", name="backend_reponse_active")
     */
    public function active(Reponse $reponse, ObjectManager $manager) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');

        $status = $reponse->getIsActive();

        if ($status ) {
            $reponse->setIsActive(false);

            $manager->persist($reponse);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'La réponse a été réactivé',
                'banish' => false,
                'type' => 'response'
            ],200);
        }

        $reponse->setIsActive(true);

        $manager->persist($reponse);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'La réponse a bien été banni',
            'banish' => true,
            'type' => 'response'
        ],200);
    }
}
