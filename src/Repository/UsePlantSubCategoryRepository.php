<?php

namespace App\Repository;

use App\Entity\UsePlantSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsePlantSubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsePlantSubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsePlantSubCategory[]    findAll()
 * @method UsePlantSubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsePlantSubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsePlantSubCategory::class);
    }

    // /**
    //  * @return UsePlantSubCategory[] Returns an array of UsePlantSubCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsePlantSubCategory
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
