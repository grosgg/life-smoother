<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProcessedLineRepository
 *
 */
class ProcessedLineRepository extends EntityRepository
{
    public function findByBoth($importId, $lineId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('pl')
            ->from('GrosComptaBundle:ProcessedLine', 'pl')
            ->where('pl.import_id = :iid')
                ->setParameter('iid', $importId)
            ->andWhere('pl.line_id = :lid')
                ->setParameter('lid', $lineId);

        return $qb->getQuery()->getResult();
    }
}
