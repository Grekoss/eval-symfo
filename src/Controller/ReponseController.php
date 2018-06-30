<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Repository\QuestionRepository;


class ReponseController extends Controller
{

    /**
     * @Route("/remove/reponse/{id}", name="reponse_delete", methods="DELETE")
     */
    public function delete(Request $request, Reponse $reponse) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reponse);
            $em->flush();
        }
        
        return $this->redirectToRoute('homepage', ['page' => 1]);
    }
      
    /**
     * @Route("/reponse/{id}/edit", name="reponse_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reponse $reponse, UserInterface $user) : Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_reponse', ['id' => $user->getId()]);
        }

        return $this->render('question/edit_reponse.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reponse/{id}/best", name="best_reponse")
     */
    public function bestReponse(Reponse $reponse, Request $request, QuestionRepository $questionRepository, UserInterface $user) : Response
    {
        // On recherche la question par rapport à l'ID
        $question = $questionRepository->findOneById($reponse->getQuestion());

        // On lui met la meilleur réponse :
        $question->setReponseValid($reponse);
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        return $this->render('profil/question.html.twig', [
            'questions' => $questionRepository->findQuestionByAuthor($user->getId()),
        ]);
    }

    /**
     * @Route("/reponse/{id}/vote", name="vote_reponse")
     */
    public function voteReponse(Reponse $reponse, Request $request, QuestionRepository $questionRepository, UserInterface $user) : Response
    {
        // On recherche la question par rapport à l'ID
        $question = $questionRepository->findOneById($reponse->getQuestion());

        // On sauvegarder l'user   => pourquoi l'user et pas +1? comme ça l'user ne peux voter qu'une fois la réponse et pas "tricher"
        $reponse->addVote($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reponse);
        $em->flush();

        return $this->redirectToRoute('homepage', ['page' => 1]);
    }
}
