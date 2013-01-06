<?php
// src/Gros/UserBundle/Entity/User.php

namespace Gros\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="GrosUser")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Gros\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="GrosUserGroup",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get group
     *
     * @return Gros\UserBundle\Entity\Group
     */
    public function getGroup()
    {
        // Even if the table relation is ManyToMany, one user should only be part of one group
        return $this->groups[0];
    }
}
