<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReponseRepository;
use App\Form\ReponseType;
use App\Entity\Reponse;


class QuestionController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('homepage', ['page' => 1]);
    }

    /**
     * @Route("/{page}/", name="homepage", defaults={"page:1"})
     */
    public function index($page, QuestionRepository $questionRepository)
    { 
        $maxQuestions = '7';

        $question_count = $questionRepository->countTotalQuestion();
        $questions = $questionRepository->findAllQuestionByRecentDatePage($page, $maxQuestions);

        $pagination = array(
            'page' => $page,
            'route' => 'homepage',
            'pages_count' => ceil($question_count / $maxQuestions),
            'route_params' => array()
        );

        return $this->render('question/index.html.twig', [
            'question_count' => $question_count,
            'questions' => $questions,
            'pagination' => $pagination,
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
     * @Route("/question/{id}/vote", name="vote_question")
     */
    public function voteReponse(Question $question, Request $request, QuestionRepository $questionRepository, UserInterface $user) : Response
    {
        
        // On sauvegarder l'user   => pourquoi l'user et pas +1? comme ça l'user ne peux voter qu'une fois la réponse et pas "tricher"
        $question->addVote($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        return $this->redirectToRoute('homepage', ['page' => 1]);
    }
}

