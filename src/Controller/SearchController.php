<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Tag;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
use App\Entity\User;

class SearchController extends Controller
{
    /**
     * @Route("/search/tag/{slug}", name="search_tag")
     */
    public function searchTag(Tag $tag, QuestionRepository $questionRepository)
    {       
        return $this->render('search/tag.html.twig', [
            'tag' => $tag,
            'questions' => $questionRepository->findQuestionByTag($tag),
        ]);
    }

    /**
     * @Route("/search", name="searchAction")
     */
    public function searchAction(Request $request, QuestionRepository $questionRepository)
    {

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            dump($data['search']);
            $text = $data['search'];
            
            
            return $this->render('search/search.html.twig', [
                'form' => $form->createView(),
                'questions' => $questionRepository->searchQuestion($text),
                'resultStatus' => 1,
                'text' => $text,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'resultStatus' => 0,
        ]);
    }

    /**
     * @Route("/search/author/{id}", name="search_author")
     */
    public function searchAuthor(User $user, QuestionRepository $questionRepository)
    {
        return $this->render('search/author.html.twig', [
            'user' => $user,
            'questions' => $questionRepository->findQuestionByAuthor($user),
        ]);
    }

}

