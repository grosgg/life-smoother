<?php

namespace Gros\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home controller.
 *
 * @Route("/home")
 */
class HomeController extends Controller
{
    /**
     * Home screen for website
     *
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('GrosCoreBundle:Home:index.html.twig');
    }
}
