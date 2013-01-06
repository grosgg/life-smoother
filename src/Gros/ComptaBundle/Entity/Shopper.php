<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shopper
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Shopper
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

     /**
     * @ORM\ManyToOne(targetEntity="Gros\UserBundle\Entity\Group")
     * @ORM\JoinColumn(nullable=true)
     */
    private $group;


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
     * Set name
     *
     * @param string $name
     * @return Shopper
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Shopper
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set group
     *
     * @param Gros\UserBundle\Entity\Group $group
     * @return Category
     */
    public function setGroup(\Gros\UserBundle\Entity\Group $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group
     *
     * @return Gros\UserBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get display name
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
