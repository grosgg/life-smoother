<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\ComptaBundle\Entity\Operation;
use Symfony\Bridge\Monolog\Logger;

class OperationHandler
{
    protected $form;
    protected $request;
    protected $em;

    public function __construct(Form $form, Request $request, EntityManager $em, Logger $logger)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->logger  = $logger;
    }

    public function process()
    {
        if($this->request->getMethod() == 'POST') {
            $this->logger->debug('Request is POST.');

            if($this->form->isValid()) {
                $this->onSuccess($this->form->getData());
                return true;
            }
        }

        return false;
    }

    public function onSuccess(Operation $operation)
    {
        $this->em->persist($operation);
        $this->em->flush();
    }
}
