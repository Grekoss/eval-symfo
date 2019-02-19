<?php

namespace App\Repository;

use App\Entity\ReponseLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReponseLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseLike[]    findAll()
 * @method ReponseLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReponseLike::class);
    }

    /**
     * Récupération de la liste des réponses liké d'un membres
     * @return ReponseLike[] Return an array of Response objects
     */
    public function findResponseLikedByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->Where('r.user = :theUser')
            ->setParameter('theUser', $user)
            ->getQuery()
            ->getResult();
    }
}
