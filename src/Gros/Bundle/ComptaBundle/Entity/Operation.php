<?php

namespace Gros\Bundle\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gros\Bundle\ComptaBundle\Entity\Operation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gros\Bundle\ComptaBundle\Entity\OperationRepository")
 */
class Operation
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer $category
     *
     * @ORM\Column(name="category", type="integer")
     */
    private $category;

    /**
     * @var integer $user
     *
     * @ORM\Column(name="user", type="integer")
     */
    private $user;

    /**
     * @var integer $shop
     *
     * @ORM\Column(name="shop", type="integer")
     */
    private $shop;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    
    public function __construct()
    {
        $this->date = new \Datetime(); // Current datetime by default
        $this->shop = 0; // Unknown shop by default
        $this->description = null; // No description by default
    }

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
     * Set amount
     *
     * @param float $amount
     * @return Operation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Operation
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Operation
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param integer $user
     * @return Operation
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set shop
     *
     * @param integer $shop
     * @return Operation
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    
        return $this;
    }

    /**
     * Get shop
     *
     * @return integer 
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Operation
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
}
