<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DefaultsType extends AbstractType
{
    private $name = 'gros_comptabundle_defaultstype';
    private $group;

    public function __construct($user)
    {
        $this->group = $user->getGroup()->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupId = $this->group;
        $builder
            ->add('category', 'entity',array(
                'class'    => 'GrosComptaBundle:Category',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
            ->add('shop', 'entity',array(
                'class'    => 'GrosComptaBundle:Shop',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('s')
                        ->where('s.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
            ->add('shopper', 'entity',array(
                'class'    => 'GrosComptaBundle:Shopper',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('s')
                        ->where('s.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gros\ComptaBundle\Entity\Defaults'
        ));
    }

    public function getName()
    {
        return 'gros_comptabundle_defaultstype';
    }
}
