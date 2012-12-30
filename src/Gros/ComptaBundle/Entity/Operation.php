<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Gros\ComptaBundle\Entity\Operation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gros\ComptaBundle\Entity\OperationRepository")
 * @Assert\Callback(methods={"isDuplicate"})
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
     * @ORM\Column(name="amount", type="decimal", scale=2)
     */
    private $amount;

    /**
     * @var integer $type
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type;

    /**
     * @var \Date $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

     /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

     /**
     * @ORM\ManyToOne(targetEntity="Gros\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(nullable=false)
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
     * Set user
     *
     * @param Gros\UserBundle\Entity\User $user
     * @return Operation
     */
    public function setUser(\Gros\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Gros\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set type
     *
     * @param boolean $type
     * @return Operation
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return boolean 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Is duplicate
     *
     * @param ExecutionContext $context
     */
    public function isDuplicate(ExecutionContext $context)
    {
        if ($this->checkDuplicate($this->getAmount(), $this->getShop(), $this->getDate()) === true) {
            $context->addViolation('This opeartion already exists in the system', array(), null);
        }
    }
}
