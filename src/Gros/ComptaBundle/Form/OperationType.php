<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class OperationType extends AbstractType
{
    private $name = 'gros_comptabundle_operationtype';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('type', 'choice', array(
                'choices' => array(
                    0 => 'Debit',
                    1 => 'Credit'
                )))
            ->add('date', 'date', array('widget' => 'single_text'))
            ->add('description')
            ->add('category', 'entity',array(
                'class'    => 'GrosComptaBundle:Category',
                'property' => 'name'))
            ->add('shop', 'entity',array(
                'class'    => 'GrosComptaBundle:Shop',
                'property' => 'name'))
            ->add('user', 'entity',array(
                'class'    => 'GrosUserBundle:User',
                'property' => 'username'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gros\ComptaBundle\Entity\Operation'
        ));
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
