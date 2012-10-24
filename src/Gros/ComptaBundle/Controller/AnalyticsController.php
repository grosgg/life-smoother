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
    public function indexAction(Request $request)
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
            $filters_form->setData(array('startDate' => date('Y-m-01'), 'endDate' => date('Y-m-30')));
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
                            ->sumByCategory('2012-10-01', '2012-10-19');

        var_dump($total_debit);
    }
}
