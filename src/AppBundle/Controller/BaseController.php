<?php

namespace AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
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