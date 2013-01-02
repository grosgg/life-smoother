<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gros\ComptaBundle\Entity\ProcessedLine
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProcessedLine
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
     * @var integer
     *
     * @ORM\Column(name="import_id", type="integer")
     */
    private $import_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="line_id", type="integer")
     */
    private $line_id;


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
     * Set import_id
     *
     * @param integer $importId
     * @return ImportedLine
     */
    public function setImportId($importId)
    {
        $this->import_id = $importId;
    
        return $this;
    }

    /**
     * Get import_id
     *
     * @return integer 
     */
    public function getImportId()
    {
        return $this->import_id;
    }

    /**
     * Set line_id
     *
     * @param integer $lineId
     * @return ImportedLine
     */
    public function setLineId($lineId)
    {
        $this->line_id = $lineId;
    
        return $this;
    }

    /**
     * Get line_id
     *
     * @return integer 
     */
    public function getLineId()
    {
        return $this->line_id;
    }
}
