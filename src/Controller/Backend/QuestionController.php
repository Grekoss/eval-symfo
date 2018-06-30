<?php

namespace App\Controller\Backend;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ReponseRepository;
use App\Entity\Reponse;

/**
 * @Route("/backend/question")
 */
class QuestionController extends Controller
{
    /**
     * @Route("/list/{page}", name="backend_question_index", methods="GET", defaults={"page:1"})
     */
    public function index($page, QuestionRepository $questionRepository): Response
    {
        $maxQuestions = '5';

        $question_count = $questionRepository->countTotalQuestionAll();
        $questions = $questionRepository->findAllQuestionByRecentDateAll($page, $maxQuestions);

        $pagination = array(
            'page' => $page,
            'route' => 'backend_question_index',
            'pages_count' => ceil($question_count / $maxQuestions),
            'route_params' => array()
        );

        return $this->render('backend/question/index.html.twig', [
            'question_count' => $question_count,
            'questions' => $questions,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="backend_question_new", methods="GET|POST")
     */
    public function new(Request $request, Slugger $slugger, UserInterface $user): Response
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

            return $this->redirectToRoute('backend_question_index', ['page' => 1]);
        }

        return $this->render('backend/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_question_show", methods="GET")
     */
    public function show(Question $question): Response
    {
        return $this->render('backend/question/show.html.twig', ['question' => $question]);
    }

    /**
     * @Route("/{id}/edit", name="backend_question_edit", methods="GET|POST")
     */
    public function edit(Request $request, Question $question, Slugger $slugger): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slugify($question->getTitle());
            $question->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_question_edit', ['id' => $question->getId()]);
        }

        return $this->render('backend/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_question_delete", methods="DELETE")
     */
    public function delete(Request $request, Question $question, ReponseRepository $reponseRepository): Response
    {
        $listReponses = $reponseRepository->findAllReponseByQuestion($question);

        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            // Suppression des réponses de la question
            foreach ($listReponses as $reponse) {
                $em->remove($reponse);
                $em->flush();
            }
            
            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('backend_question_index', ['page' => 1]);
    }

    /**
     * @Route("/{id}/active", name="backend_question_active")
     */
    public function active(Question $question, QuestionRepository $questionRepository) : Response
    {
        $status = $question->getIsActive();

        if ($status == true) {
            $question->setIsActive(false);
        }
        if ($status == false) {
            $question->setIsActive(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        return $this->redirectToRoute('backend_question_index', ['page' => 1]);
    }

    /**
     * @Route("/{id}/reponses", name="backend_question_listReponse")
     */
    public function showReponsesForQuestion(Question $question, ReponseRepository $reponseRepository)
    {
        return $this->render('backend/reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAllReponseByQuestionAll($question),
        ]);
    }
}
