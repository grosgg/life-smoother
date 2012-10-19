<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Analytics controller.
 *
 * @Route("/analytics")
 */
class AnalyticsController extends Controller
{
    /**
     * Analytics screen for ComptaGros
     *
     * @Route("/", name="analytics")
     * @Template()
     */
    public function indexAction()
    {
        $gros_charts_service = $this->container->get('gros_compta.charts');

        $data_exp_by_cat = $gros_charts_service->getChartExpensesCategory('exp_by_cat', '2012-10-15', '2012-10-18', 'pie');

        return $this->render('GrosComptaBundle:Analytics:index.html.twig', array('data_exp_by_cat' => $data_exp_by_cat));
    }

    /**
     * Analytics test page
     *
     * @Route("/test", name="analytics_test")
     * @Template()
     */
    public function testAction()
    {
        $total_debit = $this->getDoctrine()
                            ->getEntityManager()
                            ->getRepository('GrosComptaBundle:Operation')
                            ->sumAllDebit('2012-10-01', '2012-10-19');

        var_dump($total_debit);
    }
}
