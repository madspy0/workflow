<?php

namespace App\Repository;

use App\Entity\DevelopmentSolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DevelopmentSolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevelopmentSolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevelopmentSolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopmentSolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevelopmentSolution::class);
    }

    // /**
    //  * @return DevelopmentSolution[] Returns an array of DevelopmentSolution objects
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
    public function findOneBySomeField($value): ?DevelopmentSolution
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @method DevelopmentSolution[]    findAll()
     * @return DevelopmentSolution[]
     */
    public function findAll():array
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
}
