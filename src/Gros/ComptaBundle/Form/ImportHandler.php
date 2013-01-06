<?php

namespace Gros\ComptaBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gros\UserBundle\Entity\User;
use Gros\ComptaBundle\Entity\Import;

class ImportHandler
{
    protected $form;
    protected $request;
    protected $em;
    protected $user;

    public function __construct(Form $form, Request $request, EntityManager $em, User $user)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->user    = $user;
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

    public function onSuccess(Import $import)
    {
        // Automatically setting import's group
        $userGroups = $this->user->getGroups();
        $import->setGroup($userGroups[0]);

        $this->em->persist($import);
        $this->em->flush();
    }
}
