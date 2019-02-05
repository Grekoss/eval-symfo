<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserTypeAdmin;
use App\Repository\QuestionLikeRepository;
use App\Repository\ReponseLikeRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;

/**
 * @Route("/backend/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list/", name="backend_user_index", methods="GET")
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAllUserByRecentDateAll(),
            $request->query->getInt('page',1),
            10
        );

        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
            ]);
    }

    /**
     * @Route("/{id}", name="backend_user_show", methods="GET")
     */
    public function show(User $user, QuestionLikeRepository $likeRepository): Response
    {
        return $this->render('backend/user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="backend_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserTypeAdmin::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_index');
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user, ReponseRepository $reponseRepository, QuestionRepository $questionRepository, QuestionLikeRepository $questionLikeRepository, ReponseLikeRepository $reponseLikeRepository): Response
    {
        $listQuestions = $questionRepository->findQuestionByAuthor($user);
        $listReponses = $reponseRepository->findReponseByAuthor($user);
        $listQuestionsLike = $questionLikeRepository->findQuestionLikedByUser($user);
        $listReponsesLike = $reponseLikeRepository->findResponseLikedByUser($user);
        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            
            // Suppressions des likes
            // Questions:
            foreach ($listQuestionsLike as $like) {
                $em->remove($like);
                $em->flush();
            }
            // Réponses:
            foreach ($listReponsesLike as $like) {
                $em->remove($like);
                $em->flush();
            }

            // Suppression des réponses de la question de l'user
            foreach ($listReponses as $reponse) {
                $em->remove($reponse);
                $em->flush();
            }
            // Suppression des questions de l'user
            foreach ($listQuestions as $question) {
                // On vérifie si la question n'a pas réponse
                $reponsesByQuestion = $reponseRepository->findAllReponseByQuestion($question);
                // Si le cas alors on les supprimer
                foreach ($reponsesByQuestion as $reponse) {
                    $em->remove($reponse);
                    $em->flush();
                }
                
                $em->remove($question);
                $em->flush();
            }

            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('backend_user_index', ['page' => 1]);
    }
}
