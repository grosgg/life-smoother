<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\ComptaBundle\Entity\Operation;
//use Symfony\Bridge\Monolog\Logger;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class OperationHandler
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
        $logger = new Logger('my_logger');
        //$logger->debug('Je suis gros.');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../../../app/logs/gros.log', Logger::DEBUG));
        $logger->addDebug('Processing...');

        if($this->request->getMethod() == 'POST') {
            $logger->addDebug($this->request);
            $logger->addDebug($this->form->bindRequest($this->request));
            $logger->addDebug('data: ' . $this->form->getData());

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
