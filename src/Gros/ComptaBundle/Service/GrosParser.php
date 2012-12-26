<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GrosParser
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->shops = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Shop')->findAll();

        // TODO: Here only to make less queries for now
        $this->tomoko = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('tomoko');
        $this->jeremy = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('jeremy');
    }

    public function parseLabelLaBanquePostale($data)
    {
        $result = array();

        // Auto guessing shops
        foreach ($this->shops as $shop) {
            if (strpos(strtolower($data), strtolower($shop->getName()))) {
                $result['guessedShop'] = $shop->getId();
            }
        }

        // Custom guessing user
        // TODO: Make automatic it later
        if (strpos(strtolower($data), 'numero 879')) {
            $user = $this->tomoko;
        } else {
            $user = $this->jeremy;
        }
        $result['guessedUser'] = $user->getId();

        return $result;
    }
}
