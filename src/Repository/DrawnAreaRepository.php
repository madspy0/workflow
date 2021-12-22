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

    public function inBoard($geom)
    {
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare('select aa_check_obl2(ST_GeomFromText(\'' . $geom . '\')) as is_oblast');
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0]['is_oblast'];
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAreaByUser($user)
    {
//        return $this->createQueryBuilder('da')
//            ->andWhere('da.author = :user')
//            ->setParameter('user', $user)
//            ->select('SUM( CAST(da.area as decimal) ) as fullArea')
//            ->getQuery()
//            ->getOneOrNullResult();
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
            SELECT SUM(CAST(da.area as decimal)) as fullarea
            FROM drawn_area da
            INNER JOIN public.user uzer ON uzer.id = da.author_id
            WHERE da.author_id = :user
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('user' => $user->getId()));
        return $stmt->fetch();
    }
}
