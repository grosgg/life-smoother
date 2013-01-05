<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Gros\ComptaBundle\Security\GroupSecurityIdentity;
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
        $userSecurityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($userSecurityIdentity, MaskBuilder::MASK_OWNER);

        $this->aclProvider->updateAcl($acl);
        
    }

    public function checkAccess($permission, $entity)
    {
        switch ($permission) {
            case 'VIEW':
                // Only checking if user logged in is from the same group as entity
                $user = $this->securityContext->getToken()->getUser();
                if ($user->getId() == $entity->getUser()->getId()) {
                    return true;
                } else {
                    throw new AccessDeniedException();
                }
                break;

            case 'EDIT':
            case 'DELETE':
                if (false === $this->securityContext->isGranted($permission, $entity))
                {
                    throw new AccessDeniedException();
                } else {
                    return true;
                }

            default:
                throw new AccessDeniedException();
                break;
        }
    }

}
