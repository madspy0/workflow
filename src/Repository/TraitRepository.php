<?php

namespace App\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

trait TraitRepository
{
    public function getPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }
}
