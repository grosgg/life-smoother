<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class GrosSecurity
{
    protected $doctrine;

    public function __construct(Registry $doctrine, $aclProvider, $securityContext)
    {
        $this->doctrine = $doctrine;
        $this->aclProvider = $aclProvider;
        $this->securityContext = $securityContext;
    }

    public function insertAce($entity)
    {
        // creating the ACL
        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $this->aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $user = $this->securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->aclProvider->updateAcl($acl);
        
    }

    public function checkAccess($permission, $entity)
    {
        if (false === $this->securityContext->isGranted($permission, $entity))
        {
            throw new AccessDeniedException();
        } else {
            return true;
        }
    }

}
