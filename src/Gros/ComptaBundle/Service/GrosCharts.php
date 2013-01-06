<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GrosCharts
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;

    }

    public function getChartExpensesIncomes($targetDiv, $startDate, $endDate, $chartShape='ColumnChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'Time'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Incomes'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Expenses'
        );
        
        $data = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByType($startDate, $endDate);

        $rows = array();
        $expenses = !empty($data[0]) ? $data[0]['sumamount'] : 0;
        $incomes = !empty($data[1]) ? $data[1]['sumamount'] : 0;
        $rows[] = array('Total', $incomes, $expenses);

        $title = 'Expenses vs Incomes';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
                'chartShape' => $chartShape,
        );
    }

    public function getChartExpensesCategory($targetDiv, $startDate, $endDate, $chartShape='PieChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'Category'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Euros'
        );
        
        $data = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByCategory($startDate, $endDate);

        $rows = array();

        foreach($data as $dataLine){
            $rows[] = array($dataLine['name'], $dataLine['sumamount']);
        }

        $title = 'Expenses by Category';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
                'chartShape' => $chartShape,
        );
    }

    public function getChartExpensesShop($targetDiv, $startDate, $endDate, $chartShape='PieChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'Shop'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Euros'
        );
        
        $data = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByShop($startDate, $endDate);

        $rows = array();

        foreach($data as $dataLine){
            $rows[] = array($dataLine['name'], $dataLine['sumamount']);
        }

        $title = 'Expenses by Shop';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
                'chartShape' => $chartShape,
        );
    }

    public function getChartExpensesUser($targetDiv, $startDate, $endDate, $chartShape='PieChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'User'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Euros'
        );
        
        $data = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByShopper($startDate, $endDate);

        $rows = array();

        foreach($data as $dataLine){
            $rows[] = array($dataLine['name'], $dataLine['sumamount']);
        }

        $title = 'Expenses by User';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
                'chartShape' => $chartShape,
        );
    }

    public function getChartAutoEstimations($targetDiv, $monthsBase, $chartShape='LineChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'Time'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Expenses'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Incomes'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Savings'
        );

        // Deciding date range based on monthsBase param
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        switch ($monthsBase) {
            case 12:
                $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')-1));
                break;
            
            case 9:
            case 6:
            case 3:
                $startDate = date('Y-m-d', mktime(0, 0, 0, date('m')-$monthsBase, 1, date('Y')));
                break;
            
            case 1:
            default:
                $startDate = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
                break;
        }
        
        // Getting initial lines for average calculation
        $initialData = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByType($startDate, $endDate);

        // Calculating the averages based on past months operations
        $estimationBase = array(0 => 0, 1 => 0);
        foreach ($initialData as $row) {
            $estimationBase[$row['type']] = $row['sumamount'] / $monthsBase;
        }
        //print_r($startDate . ' - ' . $endDate . ' - ' . $initialData . ' - ' . $estimationBase);die;

        // Cumulating sums for each future month
        $rows = array();
        $rows[] = array('Now', 0, 0, 0);

        if (!empty($estimationBase)) {
            for($i=1; $i<=24; $i++){
                $rows[] = array($i, $rows[$i-1][1] + $estimationBase[0], $rows[$i-1][2] + $estimationBase[1], ($rows[$i-1][2] + $estimationBase[1]) - ($rows[$i-1][1] + $estimationBase[0]));
            }
        }

        $title = 'Automatic Estimations';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
                'chartShape' => $chartShape,
        );
    }
}
