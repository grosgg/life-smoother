<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\ComptaBundle\Entity\Operation;
use Gros\ComptaBundle\Entity\ProcessedLine;
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
                $this->onSuccess($this->form->getData());
                return true;
            } else {
                $this->logger->debug($this->form->getErrorsAsString());
            }
        }

        return false;
    }

    public function processAjax()
    {
        if($this->request->getMethod() == 'POST') {

            $this->form->bind($this->request);

            if($this->form->isValid()) {
                $this->onSuccess($this->form->getData());
                $this->saveProcessedLine($this->request->get('import_id'), $this->request->get('form_id'));
                return true;
            } else {
                if($this->form->hasChildren()) {
                    $errors = array();

                    // Looping through form elements to find errors
                    foreach($this->form->getChildren() as $formElement) {
                        if (!$formElement->isValid()) {
                            foreach ($formElement->getErrors() as $error) {
                                $errors[$formElement->getName()] = $error->getMessage();
                            }
                        }
                    }

                    // Even if the import failed, the line was processed and doesn't need to appear again
                    $this->saveProcessedLine($this->request->get('import_id'), $this->request->get('form_id'));

                    // We return the errors array to the Ajax caller
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

    public function saveProcessedLine($importId, $lineId)
    {
        $importedLine = new ProcessedLine;
        $importedLine->setImportId($importId);
        $importedLine->setLineId($lineId);

        $this->em->persist($importedLine);
        $this->em->flush();
    }
}
