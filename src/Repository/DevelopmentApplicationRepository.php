<?php

namespace App\Repository;

use App\Entity\DevelopmentApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DevelopmentApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevelopmentApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevelopmentApplication[]    findAll()
 * @method DevelopmentApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopmentApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevelopmentApplication::class);
    }

    // /**
    //  * @return DevelopmentApplication[] Returns an array of DevelopmentApplication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DevelopmentApplication
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
