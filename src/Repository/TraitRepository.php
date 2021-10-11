<?php

namespace App\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

trait TraitRepository
{
    public function getPaginator(int $offset, $status = ''): Paginator
    {
        $qb = $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)//        ->getQuery()
        ;
        if ($status) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $status);
        }
        return new Paginator($qb->getQuery());
    }
}
