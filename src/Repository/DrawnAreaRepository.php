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
//            ->select('SUM( TO_NUMBER(da.area) ) as fullArea')
//            ->getQuery()
//            ->execute()
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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getPartialObj($id)
    {
        $q = $this->getEntityManager()->createQuery("select partial 
            da.{
            id,
            localGoverment,
            createdAt,
            updatedAt,
            address,
            link,
            numberSolution,
            solutedAt,
            publishedAt,
            archivedAt,
            status,
            useCategory,
            useSubCategory,
            area,
            documentsType
            } 
        from App\Entity\DrawnArea da where da.id = :id")
            ->setParameter('id', $id);
        return $q->getSingleResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getPartialObjDto($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select(
                            'da.id',
                            'da.localGoverment',
                            'da.createdAt',
                            'da.updatedAt',
                            'da.address',
                            'da.link',
                            'da.numberSolution',
                            'da.solutedAt',
                            'da.publishedAt',
                            'da.archivedAt',
                            'da.status',
                            'da.area',
                            'da.documentsType'
            )
                            ->from(DrawnArea::class, 'da')
                            ->where('da.id = :id')
            ->setParameter('id', $id);

            return $queryBuilder->getQuery()->getSingleResult()->hydrateSingleResultAs(DrawnArea::class);
    }
}
