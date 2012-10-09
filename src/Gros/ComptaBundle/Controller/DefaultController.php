<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Operation;
use Gros\ComptaBundle\Form\OperationType;
use Gros\ComptaBundle\Form\OperationHandler;

/**
 * Default controller.
 *
 * @Route("/home")
 */
class DefaultController extends Controller
{
    /**
     * Home screen for compta
     *
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('GrosComptaBundle:Default:index.html.twig');
    }
}
