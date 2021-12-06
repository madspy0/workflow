<?php

namespace App\Repository;

use App\Entity\DzkAdminOtg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DzkAdminOtg|null find($id, $lockMode = null, $lockVersion = null)
 * @method DzkAdminOtg|null findOneBy(array $criteria, array $orderBy = null)
 * @method DzkAdminOtg[]    findAll()
 * @method DzkAdminOtg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DzkAdminOtgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DzkAdminOtg::class);
    }

    // /**
    //  * @return DzkAdminOtg[] Returns an array of DzkAdminOtg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DzkAdminOtg
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
