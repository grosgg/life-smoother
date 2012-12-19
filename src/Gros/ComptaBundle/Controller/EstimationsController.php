<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Form\AnalyticsFiltersType;
use Gros\ComptaBundle\Form\AnalyticsFiltersHandler;

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
     * @Route("/automatic", name="estimations_automatic")
     * @Template()
     */
    public function AutomaticAction(Request $request)
    {
        $gros_charts_service = $this->container->get('gros_compta.charts');

        $filters_form = $this->createForm(new AnalyticsFiltersType());
        $formHandler = new AnalyticsFiltersHandler($filters_form, $this->get('request'));
        if ($formHandler->process()) {
            $data = $filters_form->getData();
            $data_op_by_type = $gros_charts_service->getChartExpensesIncomes('op_by_type', $data['startDate'], $data['endDate'], 'ColumnChart');
            $data_exp_by_cat = $gros_charts_service->getChartExpensesCategory('exp_by_cat', $data['startDate'], $data['endDate'], 'PieChart');
            $data_exp_by_shop = $gros_charts_service->getChartExpensesShop('exp_by_shop', $data['startDate'], $data['endDate'], 'PieChart');
            $data_exp_by_user = $gros_charts_service->getChartExpensesUser('exp_by_user', $data['startDate'], $data['endDate'], 'PieChart');
        } else {
            $data_op_by_type = $gros_charts_service->getChartExpensesIncomes('op_by_type', date('Y-m-01'), date('Y-m-30'), 'ColumnChart');
            $data_exp_by_cat = $gros_charts_service->getChartExpensesCategory('exp_by_cat', date('Y-m-01'), date('Y-m-30'), 'PieChart');
            $data_exp_by_shop = $gros_charts_service->getChartExpensesShop('exp_by_shop', date('Y-m-01'), date('Y-m-30'), 'PieChart');
            $data_exp_by_user = $gros_charts_service->getChartExpensesUser('exp_by_user', date('Y-m-01'), date('Y-m-30'), 'PieChart');
            $filters_form->setData(array('startDate' => date_create_from_format('Y-m-d', date('Y-m-01')), 'endDate' => date_create_from_format('Y-m-d', date('Y-m-30'))));
        }

        return $this->render('GrosComptaBundle:Analytics:index.html.twig', array(
            'data_op_by_type' => $data_op_by_type,
            'data_exp_by_cat' => $data_exp_by_cat,
            'data_exp_by_shop' => $data_exp_by_shop,
            'data_exp_by_user' => $data_exp_by_user,
            'filters_form' => $filters_form->createView()
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
