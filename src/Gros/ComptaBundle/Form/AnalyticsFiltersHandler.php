<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class AnalyticsFiltersHandler
{
    protected $form;
    protected $request;
    protected $em;

    public function __construct(Form $form, Request $request)
    {
        $this->form    = $form;
        $this->request = $request;
    }

    public function process()
    {
        if($this->request->getMethod() == 'POST') {
            $this->form->bindRequest($this->request);

            if($this->form->isValid()) {
                $this->onSuccess($this->form->getData());
                return true;
            }
        }

        return false;
    }

    public function onSuccess()
    {
    }
}
