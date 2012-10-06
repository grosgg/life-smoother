<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GrosComptaBundle:Default:index.html.twig', array('name' => $name));
    }
}
