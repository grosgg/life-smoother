<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GrosPaginator
{
    protected $doctrine;
    protected $group;
    protected $limit;

    public function __construct(Registry $doctrine, $securityContext, $paginationLimit)
    {
        $this->doctrine = $doctrine;
        $this->group = $securityContext->getToken()->getUser()->getGroup()->getId();
        $this->limit = $paginationLimit;
    }

    public function createPaginator($entityName, $pageCurrent, $route)
    {
        $countTotal = count($this->doctrine->getManager()->getRepository($entityName)->findBy(array('group' => $this->group)));
        $pagesTotal = ceil($countTotal / $this->limit);
        $paginator = array(
            'pageCurrent' => $pageCurrent,
            'pagesTotal' => $pagesTotal,
            'routeName' => $route,
        );
        return $paginator;
    }

    public function getPageRows($entityName, $pageCurrent) {
        return $this->doctrine->getManager()->getRepository($entityName)->findBy(array('group' => $this->group), array(), $this->limit, $this->limit * ($pageCurrent -1));
    }

}
