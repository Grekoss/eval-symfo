<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    /**
     * Récupérer la liste completes des réponses avec les bannis
     * @return Reponse[] Returns  an array of Reponse Objects
      */
    public function findAllReponseByRecentDateAll()
    {
        $query = $this->createQueryBuilder('r')
                      ->OrderBy('r.createdAt', 'DESC');

        return $query->getQuery()->getResult();
    }

    /**
     * Récupérer la liste des réponses en fonction de la question,
     * avec l'option active pour la visu des membres
     * @return Reponse[] Returns an array of Reponse objects
     */
    public function findAllReponseByQuestion($question)
    {
        $query = $this->createQueryBuilder('r')
                      ->OrderBy('r.createdAt', 'DESC')
                      ->Where('r.isActive = 1')
                      ->andWhere('r.question = :myQuestion')
                      ->SetParameter('myQuestion', $question);

        return $query->getQuery()->getResult();
    }

    /**
     * Récupérer la liste des réponses en fonction de la question pour Modérateur
     * @return Reponse[] Returns an array of Reponse objects
     */
    public function findAllReponseByQuestionAll($question)
    {
        $query = $this->createQueryBuilder('r')
            ->OrderBy('r.createdAt', 'DESC')
            ->Where('r.question = :myQuestion')
            ->SetParameter('myQuestion', $question);

        return $query->getQuery()->getResult();
    }

    /**
     * Récupération de la liste des réponses d'un membre sans les banni
     * (Verion PMA : "SELECT r .* , q . title FROM reponse r LEFT JOIN question q ON q . id = r . question_id WHERE r . author_id = 1188" )
     * @return Reponse[] Returns an array of Reponse objects
     */
    public function findReponseByAuthor($idAuthor)
    {
        return $this->createQueryBuilder('r')
            ->Join('r.question', 'q')
            ->addSelect('q')
            ->Where('r.author = :theAuthor')
            ->andWhere('r.isActive = 1')
            ->setParameter('theAuthor', $idAuthor)
            ->getQuery()
            ->getResult();
    }
}
