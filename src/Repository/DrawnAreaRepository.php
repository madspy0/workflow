<?php

namespace App\Repository;

use App\Entity\DrawnArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrawnArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrawnArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrawnArea[]    findAll()
 * @method DrawnArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawnAreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrawnArea::class);
    }
    /**
     * Перевіряє валідність полігону
     *
     * @param $geom
     *
     * @return boolean
     */
    public function isValid($geom)
    {
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare('select ST_IsValid(ST_GeomFromText(\'' . $geom . '\')) = true as is_valid');
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0]['is_valid'];
    }
    // /**
    //  * @return DrawnArea[] Returns an array of DrawnArea objects
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
    public function findOneBySomeField($value): ?DrawnArea
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
