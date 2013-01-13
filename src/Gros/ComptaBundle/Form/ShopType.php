<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ShopType extends AbstractType
{
    private $group;

    public function __construct($user)
    {
        $this->group = $user->getGroup()->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupId = $this->group;
        $builder
            ->add('name')
            ->add('description')
            ->add('default_category', 'entity', array(
                'class'    => 'GrosComptaBundle:Category',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gros\ComptaBundle\Entity\Shop'
        ));
    }

    public function getName()
    {
        return 'gros_comptabundle_shoptype';
    }
}
