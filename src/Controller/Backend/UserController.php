<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserTypeAdmin;
use App\Repository\UserRepository;
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
class UserController extends Controller
{
    /**
     * @Route("/list/{page}", name="backend_user_index", methods="GET", defaults={"page:1"})
     */
    public function index($page, UserRepository $userRepository): Response
    {
        $maxUsers = '5';

        $user_count = $userRepository->countTotalUserAll();
        $users = $userRepository->findAllUserByRecentDateAll($page, $maxUsers);

        $pagination = array(
            'page' => $page,
            'route' => 'backend_user_index',
            'pages_count' => ceil($user_count / $maxUsers),
            'route_params' => array()
        );

        return $this->render('backend/user/index.html.twig', [
            'user_count' => $user_count,
            'users' => $users,
            'pagination' => $pagination,
            ]);
    }

    /**
     * @Route("/{id}", name="backend_user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('backend/user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="backend_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserTypeAdmin::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user, ReponseRepository $reponseRepository, QuestionRepository $questionRepository): Response
    {
        
        $listQuestions = $questionRepository->findQuestionByAuthor($user);
        $listReponses = $reponseRepository->findReponseByAuthor($user);

        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            
            
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
