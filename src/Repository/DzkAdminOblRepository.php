<?php

namespace App\Repository;

use App\Entity\DzkAdminObl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DzkAdminObl|null find($id, $lockMode = null, $lockVersion = null)
 * @method DzkAdminObl|null findOneBy(array $criteria, array $orderBy = null)
 * @method DzkAdminObl[]    findAll()
 * @method DzkAdminObl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DzkAdminOblRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DzkAdminObl::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNameRgn($oblast)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id = :oblast_id')
            ->setParameter('oblast_id', $oblast)
            ->select('Partial o.{id, nameRgn}')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
