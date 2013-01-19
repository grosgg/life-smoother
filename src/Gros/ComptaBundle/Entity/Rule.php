<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Rule
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gros\ComptaBundle\Entity\RuleRepository")
 * @UniqueEntity({"group", "priority"})
 */
class Rule
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
     * @ORM\Column(name="regex", type="string", length=100)
     */
    private $regex;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="smallint")
     * @Assert\Range(min="1", max="50")
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100)
     */
    private $description;

     /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private $category;

     /**
     * @ORM\ManyToOne(targetEntity="Shopper")
     * @ORM\JoinColumn(nullable=true)
     */
    private $shopper;

     /**
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(nullable=true)
     */
    private $shop;

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
     * Set regex
     *
     * @param string $regex
     * @return Rule
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    
        return $this;
    }

    /**
     * Get regex
     *
     * @return string 
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Rule
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Rule
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
     * Set category
     *
     * @param Gros\ComptaBundle\Entity\Category $category
     * @return Operation
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Gros\ComptaBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set shop
     *
     * @param Gros\ComptaBundle\Entity\Shop $shop
     * @return Operation
     */
    public function setShop(Shop $shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * Get shop
     *
     * @return Gros\ComptaBundle\Entity\Shop 
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set shopper
     *
     * @param Gros\ComptaBundle\Entity\Shopper $shopper
     * @return Operation
     */
    public function setShopper(Shopper $shopper)
    {
        $this->shopper = $shopper;
        return $this;
    }

    /**
     * Get shopper
     *
     * @return Gros\ComptaBundle\Entity\Shopper
     */
    public function getShopper()
    {
        return $this->shopper;
    }

    /**
     * Set group
     *
     * @param Gros\UserBundle\Entity\Group $group
     * @return Operation
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

}
