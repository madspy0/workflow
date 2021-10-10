<?php

namespace App\Repository;

use App\Entity\DevelopmentSolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DevelopmentSolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevelopmentSolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevelopmentSolution[]    findAll()
 * @method DevelopmentSolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopmentSolutionRepository extends ServiceEntityRepository
{
    use TraitRepository;
    public const PAGINATOR_PER_PAGE = 3;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevelopmentSolution::class);
    }
}
