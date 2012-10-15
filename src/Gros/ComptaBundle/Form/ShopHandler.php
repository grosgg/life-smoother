<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\ComptaBundle\Entity\Shop;

class ShopHandler
{
    protected $form;
    protected $request;
    protected $em;

    public function __construct(Form $form, Request $request, EntityManager $em)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
    }

    public function process()
    {

        if($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);
            //var_dump($this->form);

            if($this->form->isValid()) {
                var_dump('Shop form is valid');
                $this->onSuccess($this->form->getData());
                return true;
            }

            var_dump('Shop form is invalid');
        }

        return false;
    }

    public function onSuccess(Shop $shop)
    {
        $this->em->persist($shop);
        $this->em->flush();
    }
}
