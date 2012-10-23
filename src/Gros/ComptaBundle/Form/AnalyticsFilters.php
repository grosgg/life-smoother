<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnalyticsFilters extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 'date', array('widget' => 'single_text','label' => 'Start Date'))
            ->add('endDate', 'date', array('widget' => 'single_text','label' => 'End Date'))
        ;
    }

    public function getName()
    {
        return 'gros_comptabundle_analyticsfilters';
    }
}
