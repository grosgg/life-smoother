<?php

namespace Gros\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Gros\ComptaBundle\Entity\Import
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gros\ComptaBundle\Entity\ImportRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Import
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
     * @var integer $status
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var \Datetime $date
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $date;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @Assert\File(maxSize="50000")
     */
    private $file;


    public function __construct()
    {
        $this->date = new \Datetime(); // Current datetime by default
        $this->status = 0; // Status Pending by default
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

    // file methods
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/imports';
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postUpload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    public function parseLaBanquePostale($grosParserService)
    {
        $result = array();
        $row = 0;

        if (($handle = fopen($this->getAbsolutePath(), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $parsedDate = date_parse_from_format('d/m/Y', $data[0]);

                if(checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year'])) {
                    $result[$row]['date'] = $parsedDate['year'] . '-' . $parsedDate['month'] . '-' . $parsedDate['day'];
                    $result[$row]['label'] = $data[1];

                    if ($data[2] < 0) {
                        $result[$row]['type'] = 0;
                    } else {
                        $result[$row]['type'] = 1;
                    }
                    $result[$row]['signedAmount'] = floatval(str_replace(',', '.', $data[2]));
                    $result[$row]['absoluteAmount'] = abs($result[$row]['signedAmount']);

                    $result[$row]['parsedLabel'] = $grosParserService->parseLabelLaBanquePostale($data[1]);
                    $row++;
                }

            }
            fclose($handle);
        }

        //var_dump($result[0]);
        return $result;
    }

}
