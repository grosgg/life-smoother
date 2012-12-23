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
 * @Route("/estimations")
 */
class EstimationsController extends Controller
{
    /**
     * Automatic Estimations screen for ComptaGros
     *
     * @Route("/automatic/{base}", defaults={"base"="1"}, name="estimations_automatic")
     * @Template()
     */
    public function automaticAction(Request $request)
    {
        $grosChartsService = $this->container->get('gros_compta.charts');

        $dataAutoEstimations = $grosChartsService->getChartAutoEstimations('auto_estimations', $request->get('base'), 'LineChart');

        return $this->render('GrosComptaBundle:Estimations:automatic.html.twig', array(
            'data_auto_estimations' => $dataAutoEstimations,
            'base'                  => $request->get('base')
        ));
    }

    /**
     * Manual Estimations screen for ComptaGros
     *
     * @Route("/manual", name="estimations_manual")
     * @Template()
     */
    public function manualAction(Request $request)
    {
    }

    /**
     * Estimations test page
     *
     * @Route("/test", name="estimations_test")
     * @Template()
     */
    public function testAction()
    {
        $grosChartsService = $this->container->get('gros_compta.charts');

        $dataAutoEstimations = $grosChartsService->getChartAutoEstimations('auto_estimations', 6, 'LineChart');

        return $this->render('GrosComptaBundle:Estimations:automatic.html.twig', array(
            'data_auto_estimations' => $dataAutoEstimations
        ));
    }
}
