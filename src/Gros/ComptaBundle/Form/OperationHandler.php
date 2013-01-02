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

            $this->form->bind($this->request);

            if($this->form->isValid()) {
                $this->logger->debug('Form is valid.');

                $this->onSuccess($this->form->getData());
                return true;
            } else {
                $this->logger->debug('Form is invalid.');

                if($this->request->isXmlHttpRequest() && $this->form->hasChildren()) {
                    $errors = array();

                    // Looping through form elements to find errors
                    foreach($this->form->getChildren() as $formElement) {
                        if (!$formElement->isValid()) {
                            foreach ($formElement->getErrors() as $error) {
                                $errors[$formElement->getName()] = $error->getMessage();
                            }
                        }
                    }
                    die(json_encode(array('status' => false, 'errors' => $errors, 'form' => $this->request->get('form_id'))));
                }

                $this->logger->debug($this->form->getErrorsAsString());
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
