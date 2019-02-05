<?php

namespace App\Controller\Backend;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use App\Repository\ReponseRepository;

/**
 * @Route("/backend/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/list/", name="backend_question_index", methods="GET")
     */
    public function index(QuestionRepository $questionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $questions = $paginator->paginate(
            $questionRepository->findAllQuestionByRecentDateAll(true),
            $request->query->getInt('page',1),
            10
        );

        return $this->render('backend/question/index.html.twig', [
            'questions' => $questions,
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

            return $this->redirectToRoute('backend_question_index');
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
    public function active(Question $question, ObjectManager $manager) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');

        $status = $question->getIsActive();

        if ( $status ) {
            $question->setIsActive(false);

            $manager->persist($question);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'La question a bien été réactivé',
                'banish' => false,
                'type' => 'question'
            ], 200);
        }

        $question->setIsActive(true);

        $manager->persist($question);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'La question a bien été banni',
            'banish' => true,
            'type' => 'question'
        ], 200);
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
