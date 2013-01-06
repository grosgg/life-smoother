<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperationRepository extends EntityRepository
{
    public function sumByType($startDate, $endDate)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('o.type', 'SUM(o.amount) as sumamount')
            ->from('GrosComptaBundle:Operation', 'o');

        $qb = $this->setPeriod($qb, $startDate, $endDate);

        $qb->groupBy('o.type');

        return $qb->getQuery()
            ->getResult();
    }

    public function sumByCategory($startDate, $endDate)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c.name', 'SUM(o.amount) as sumamount')
            ->from('GrosComptaBundle:Operation', 'o')
            ->leftJoin('o.category', 'c')
            ->where('o.type = :type')
                ->setParameter('type', 0);

        $qb = $this->setPeriod($qb, $startDate, $endDate);

        $qb->groupBy('o.category');

        return $qb->getQuery()
            ->getResult();
    }

    public function sumByShop($startDate, $endDate)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('s.name', 'SUM(o.amount) as sumamount')
            ->from('GrosComptaBundle:Operation', 'o')
            ->leftJoin('o.shop', 's')
            ->where('o.type = :type')
                ->setParameter('type', 0);

        $qb = $this->setPeriod($qb, $startDate, $endDate);

        $qb->groupBy('o.shop');

        return $qb->getQuery()
            ->getResult();
    }

    public function sumByShopper($startDate, $endDate)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('s.name', 'SUM(o.amount) as sumamount')
            ->from('GrosComptaBundle:Operation', 'o')
            ->leftJoin('o.shopper', 's')
            ->where('o.type = :type')
                ->setParameter('type', 0);

        $qb = $this->setPeriod($qb, $startDate, $endDate);

        $qb->groupBy('o.shopper');

        return $qb->getQuery()
            ->getResult();
    }

    private function setPeriod(\Doctrine\ORM\QueryBuilder $qb, $startDate, $endDate)
    {
        $qb->andWhere('o.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate);

        return $qb;
    }

    public function checkDuplicate($amount, $shop, $date)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('o.id')
            ->from('GrosComptaBundle:Operation', 'o')
            ->where('o.amount = :amount')
                ->setParameter('amount', $amount)
            ->andWhere('o.shop = :shop')
                ->setParameter('shop', $shop)
            ->andWhere('o.date = :date')
                ->setParameter('date', $date);

        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }
}

