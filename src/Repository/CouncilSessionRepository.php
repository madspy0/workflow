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
    use TraitRepository;
    public const PAGINATOR_PER_PAGE = 3;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouncilSession::class);
    }

    public function findByDate($date)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isAt = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllDates()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.isAt')
            ->getQuery();
        return $qb->getArrayResult();
    }
}
