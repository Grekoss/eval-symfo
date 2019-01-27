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

    // /**
    //  * @return ReponseLike[] Returns an array of ReponseLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseLike
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
