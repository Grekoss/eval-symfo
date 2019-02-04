<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\ReponseLike;
use App\Form\ReponseType;
use App\Repository\ReponseLikeRepository;
use App\Repository\ReponseRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Repository\QuestionRepository;


class ReponseController extends AbstractController
{

    /**
     * @Route("/remove/reponse/{id}/{token}", name="reponse_delete", methods="DELETE", options={"expose"=true})
     */
    public function delete(Reponse $reponse, $token, ReponseRepository $reponseRepository)
    {
        if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reponse);
            $em->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Suppression de la réponse',
                'nbReponse' => $reponseRepository->count(['question' => $reponse->getQuestion()])
            ],200);
        }

        return $this->json([
            'code' => 403,
            'message' => 'Vous n\'avez pas les droits pour la suppression de la réponse!',
            'nbReponse' => $reponseRepository->count(['question' => $reponse->getQuestion()])
        ], 403);
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
     * Permet de liker ou unliker une réponse
     *
     * @Route("/reponse/{id}/vote", name="reponse_like")
     *
     * @param Reponse $reponse
     * @param ObjectManager $manager
     * @param ReponseLikeRepository $likeRepo
     * @return Response
     */
    public function likeReponse(Reponse $reponse, ObjectManager $manager, ReponseLikeRepository $likeRepo) : Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($reponse->isReponseLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'reponse' => $reponse,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['reponse' => $reponse])
            ],200);
        }

        $like = new ReponseLike();
        $like->setReponse($reponse)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepo->count(['reponse' => $reponse])
        ],200);
    }
}
