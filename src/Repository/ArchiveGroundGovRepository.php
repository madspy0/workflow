<?php

namespace App\Repository;

use App\Entity\ArchiveGroundGov;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArchiveGroundGov|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchiveGroundGov|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchiveGroundGov[]    findAll()
 * @method ArchiveGroundGov[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchiveGroundGovRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchiveGround::class);
    }

    // /**
    //  * @return ArchiveGround[] Returns an array of ArchiveGround objects
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
    public function findOneBySomeField($value): ?ArchiveGround
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
