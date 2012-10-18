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

    public function getChartExpensesIncomes($targetDiv, $startDate, $endDate, $chartShape='line')
    {

    }

    public function getChartExpensesCategory($targetDiv, $startDate, $endDate, $chartShape='pie')
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

        $rows = array();

        $rows[] = array('Food', 160);
        $rows[] = array('Rent', 760);
        $rows[] = array('Transportation', 130);

        $title = 'Expenses by Category';

        return array(
                'columns' => $columns,
                'rows' => $rows,
                'title' => $title,
                'target' => $targetDiv,
        );
    }
}
