<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Récupérer la liste des users du plus récents au plus ancien suite à la modification
     * @return User[] Returns an array of User objects
     */
    public function findAllUserByRecentDateAll()
    {
        return $this->createQueryBuilder('u')
            ->OrderBy('u.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
