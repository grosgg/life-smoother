<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\ComptaBundle\Entity\Rule;
use Gros\UserBundle\Entity\User;
use Symfony\Bridge\Monolog\Logger;

class RuleHandler
{
    protected $form;
    protected $request;
    protected $em;
    protected $user;
    protected $logger;

    public function __construct(Form $form, Request $request, EntityManager $em, User $user, Logger $logger)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->user    = $user;
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

    public function onSuccess(Rule $rule)
    {
        $this->em->persist($rule);
        $this->em->flush();
    }

}
