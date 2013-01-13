<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defaults
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gros\ComptaBundle\Entity\DefaultsRepository")
 */
class Defaults
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
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $category;

     /**
     * @ORM\ManyToOne(targetEntity="Shopper")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $shopper;

     /**
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
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

}
