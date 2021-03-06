<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionLike;
use App\Form\QuestionType;
use App\Repository\QuestionLikeRepository;
use App\Repository\QuestionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ReponseRepository;
use App\Form\ReponseType;
use App\Entity\Reponse;


class QuestionController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(QuestionRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(PaginatorInterface $paginator, AuthorizationCheckerInterface $authorizationChecker, Request $request)
    {
        //Vérification si visiteur ou simple utilisateur
        $admin = false;
        if ( true === $authorizationChecker->isGranted('ROLE_MODERATOR')) {
            $admin = true;
        }

        $questions = $paginator->paginate(
            $this->repository->findAllQuestionByRecentDateAll($admin),
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/question/{slug}", name="question_show")
     */
    public function showQuestion(Question $question, ReponseRepository $reponseRepository, Request $request)
    {
        if (!empty($this->getUser())) {

            $user = $this->getUser();

            $reponse = new Reponse();
            $form = $this->createForm(ReponseType::class, $reponse);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    
                $reponse->setQuestion($question);
                $reponse->setAuthor($user);
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();
            }
    
            return $this->render('question/show.html.twig', [
                'form' => $form->createView(),
                'question' => $question,
                'reponses' => $reponseRepository->findAllReponseByQuestion($question),
            ]);
        }
        
            return $this->render('question/show.html.twig', [
                'question' => $question,
                'reponses' => $reponseRepository->findAllReponseByQuestion($question),
            ]);
    }

    /**
     * @Route("/new/question", name="question_new", methods="GET|POST")
     */
    public function newQuestion(Request $request, Slugger $slugger, UserInterface $user) : Response
    {

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On slugge le titre
            $slug = $slugger->slugify($question->getTitle());
            $question->setSlug($slug);
            $question->setAuthor($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('homepage', ['page' => 1]);
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/remove/question/{id}", name="question_delete", methods="DELETE")
     */
    public function delete(Request $request, Question $question, ReponseRepository $reponseRepository) : Response
    {
        $listReponses = $reponseRepository->findAllReponseByQuestion($question);

        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            
            // Suppression des réponses de la question
            foreach ($listReponses as $reponse) {
                $em->remove($reponse);
                $em->flush();
            }

            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('homepage', ['page' => 1]);
    }

    /**
     * @Route("question/{id}/edit", name="question_edit", methods="GET|POST")
     */
    public function edit(Request $request, Question $question, UserInterface $user, Slugger $slugger) : Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slugify($question->getTitle());
            $question->setSlug($slug);
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_question', ['id' => $user->getId()]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de liker ou unliker une question
     *
     * @Route("/question/{id}/like", name="question_like")
     *
     * @param Question $question
     * @param ObjectManager $manager
     * @param QuestionLikeRepository $likeRepo
     * @return Response
     */
    public function likeQuestion(Question $question, ObjectManager $manager, QuestionLikeRepository $likeRepo) : Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if($question->isQuestionLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'question' => $question,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['question' => $question])
            ], 200);
        }

        $like = new QuestionLike();
        $like->setQuestion($question)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepo->count(['question' => $question])
        ], 200);
    }
}

