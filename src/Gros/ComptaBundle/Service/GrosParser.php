<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GrosParser
{
    protected $doctrine;
    protected $shops;
    protected $categories;
    protected $defaults;
    protected $processedLineRepository;

    public function __construct(Registry $doctrine, $securityContext)
    {
        $this->doctrine = $doctrine;

        $group = $securityContext->getToken()->getUser()->getGroup()->getId();
        $this->shops = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Shop')->findByGroup($group);
        $this->categories = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Category')->findByGroup($group);
        $this->defaults = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Defaults')->findOneByGroup($group);
        $this->processedLineRepository = $this->doctrine->getManager()->getRepository('GrosComptaBundle:ProcessedLine');

        // TODO: Here only to make less queries for now
        //$this->tomoko = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('tomoko');
        //$this->jeremy = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('jeremy');
    }

    public function parseLabelLaBanquePostale($data)
    {
        $result = array(
            'guessedCategory' => null,
            'guessedShop'     => null,
            'guessedShopper'  => null,
        );

        // Auto guessing shops
        foreach ($this->shops as $shop) {
            if (strpos(strtolower($data), strtolower($shop->getName()))) {
                $result['guessedShop'] = $shop->getId();
                $result['guessedCategory'] = $shop->getDefaultCategory()->getId();
            }
        }

        // Custom guessing user
        // TODO: Make automatic it later
        //if (strpos($data, '879') !== false) {
            //$user = $this->tomoko;
        //} else {
            //$user = $this->jeremy;
        //}
        //$result['guessedUser'] = $user->getId();

        // Applying default settings
        if ($this->defaults) {
            if (!$result['guessedShop']) {
                $result['guessedShop'] = $this->defaults->getShop()->getId();
            }
            if (!$result['guessedCategory']) {
                $result['guessedCategory'] = $this->defaults->getShop()->getDefaultCategory()->getId();
            }
            if (!$result['guessedShopper']) {
                $result['guessedShopper'] = $this->defaults->getShopper()->getId();
            }
        }

        return $result;
    }

    public function checkProcessedLine($importId, $lineId)
    {
        $result = $this->processedLineRepository->findByBoth($importId, $lineId);

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

}
