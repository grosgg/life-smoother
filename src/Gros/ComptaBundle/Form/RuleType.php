<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RuleType extends AbstractType
{
    private $name = 'gros_comptabundle_ruletype';
    private $group;

    public function __construct($user)
    {
        $this->group = $user->getGroup()->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupId = $this->group;
        $priorities = array();
        for ($i=1; $i<=50; $i++) {
            $priorities[$i] = $i;
        }

        $builder
            ->add('regex')
            ->add('priority', 'choice', array('choices' => $priorities))
            ->add('description')
            ->add('category', 'entity', array(
                'empty_value'   => 'None',
                'required'      => false,
                'class'         => 'GrosComptaBundle:Category',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
            ->add('shop', 'entity', array(
                'empty_value'   => 'None',
                'required'      => false,
                'class'         => 'GrosComptaBundle:Shop',
                'query_builder' => function(EntityRepository $er) use ($groupId) {
                    return $er->createQueryBuilder('s')
                        ->where('s.group = :groupId')
                        ->setParameter('groupId', $groupId);
                }
            ))
            ->add('shopper', 'entity', array(
                'empty_value'   => 'None',
                'required'      => false,
                'class'         => 'GrosComptaBundle:Shopper',
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
            'data_class' => 'Gros\ComptaBundle\Entity\Rule'
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
