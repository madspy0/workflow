<?php

namespace App\Repository;

use App\Entity\DevelopmentApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method DevelopmentApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevelopmentApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevelopmentApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopmentApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevelopmentApplication::class);
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

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function isPolygonValid($geom): array
    {
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare('select ST_MakePolygon( ST_GeomFromText(\'' . $geom . '\') ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @method DevelopmentApplication[]    findAll()
     * @return DevelopmentApplication[]
     */
    public function findAll():array
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }

    public const PAGINATOR_PER_PAGE = 3;

    public function getApplPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('da')
            ->orderBy('da.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
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
