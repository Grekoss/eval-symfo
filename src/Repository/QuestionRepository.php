<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Récupérer la liste des questions du plus récents au plus ancien pour Admin (isActive et isNotActive)
     * @return Question[] Returns an array of Question objects
     */
    public function findAllQuestionByRecentDateAll($page= 1, $maxperpage)
    {
        $query = $this->createQueryBuilder('q')
                      ->OrderBy('q.createdAt', 'DESC');

        $query->setFirstResult(($page-1) * $maxperpage)
              ->setMaxResults($maxperpage);

        return new Paginator($query);
    }

    public function countTotalQuestionAll()
    {
        $query = $this->createQueryBuilder('q')
            ->select('COUNT(q)');

        return $count = $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Récupérer la liste des questions du plus récents au plus ancien pour User (isActive seulement) avec la pagination
     * @return Question[] Returns an array of Question objects
     */
    public function findAllQuestionByRecentDatePage($page=1, $maxperpage)
    {
        $query = $this->createQueryBuilder('q')
                      ->OrderBy('q.createdAt', 'DESC')
                      ->Where('q.isActive = 1');

        $query->setFirstResult(($page-1) * $maxperpage)
              ->setMaxResults($maxperpage);

        return new Paginator($query);
    }

    public function countTotalQuestion()
    {
        $query = $this->createQueryBuilder('q')
                      ->select('COUNT(q)')
                      ->where('q.isActive = 1');
            
        return $count = $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Récupération de la liste des questions d'un membres
     * @return Question[] Returns an array of Question objects
     */
     public function findQuestionByAuthor($idAuthor)
     {
        return $this->createQueryBuilder('q')
                    ->OrderBy('q.createdAt', 'DESC')
                    ->Where('q.author = :theAuthor')
                    ->andWhere('q.isActive = :active')
                    ->setParameter('theAuthor', $idAuthor)
                    ->setParameter('active', 1)
                    ->getQuery()
                    ->getResult();
     }

    /**
     * Récupération des questions trié par TAG sans les banni
     * (Verion PMA : "SELECT q .* FROM question q LEFT JOIN question_tag ON q . id = question_tag . question_id LEFT JOIN tag t ON t . id = question_tag . tag_id WHERE t . id = 5 AND q.is_active = 1" )
     * @return Question[] Returns an array of Question objects
     */
    public function findQuestionByTag($tag)
    {
        return $this->createQueryBuilder('q')
                    ->Join('q.tags', 't')
                    ->addSelect('t')
                    ->Where('t.id = :theTag')
                    ->andWhere('q.isActive = 1')
                    ->setParameter('theTag', $tag)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Recherche des questions contenant la texte souhaité dans le body et/ou titre
     * @return Question[] Returns an array of Question objects
     */
    public function searchQuestion($text)
    {
        return $this->createQueryBuilder('q')
                    ->Where('q.title LIKE :theTitle')
                    ->andWhere('q.isActive = 1')
                    ->setParameter('theTitle', '%'.$text.'%')
                    ->getQuery()
                    ->getResult();
    }
}
