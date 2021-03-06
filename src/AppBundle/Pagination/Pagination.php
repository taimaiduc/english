<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class Pagination
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param int $maxPerPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    protected function getPagerfanta(QueryBuilder $queryBuilder, $maxPerPage = 10, $currentPage = 1)
    {
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }
}