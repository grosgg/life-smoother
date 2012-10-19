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

    public function getChart()
    {
        return 'Je mange du caca.';
    }

    public function getChartExpensesIncomes($targetDiv, $startDate, $endDate, $chartShape='ColumnChart')
    {
        $columns = array();

        $columns[] = array(
            'type' => 'string',
            'label' => 'Expenses / Incomes'
        );
        $columns[] = array(
            'type' => 'number',
            'label' => 'Euros'
        );
        
        $data = $this->doctrine->getEntityManager()
            ->getRepository('GrosComptaBundle:Operation')
            ->sumByType($startDate, $endDate);

        $rows = array();

        foreach($data as $dataLine){
            $rows[] = array($dataLine['type'], $dataLine['sumamount']);
        }

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
            ->sumByUser($startDate, $endDate);

        $rows = array();

        foreach($data as $dataLine){
            $rows[] = array($dataLine['username'], $dataLine['sumamount']);
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
}
