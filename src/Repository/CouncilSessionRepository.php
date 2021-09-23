<?php

namespace App\Repository;

use App\Entity\CouncilSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CouncilSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouncilSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouncilSession[]    findAll()
 * @method CouncilSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouncilSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouncilSession::class);
    }

    // /**
    //  * @return CouncilSession[] Returns an array of CouncilSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CouncilSession
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
