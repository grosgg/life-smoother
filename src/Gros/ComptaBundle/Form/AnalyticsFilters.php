<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnalyticsFilters extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 'date')
            ->add('endDate', 'date')
        ;
    }

    public function getName()
    {
        return 'gros_comptabundle_analyticsfilters';
    }
}
