<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldType
 *
 * @ORM\Table(name="field_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FieldTypeRepository")
 */
class FieldType
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="header_en", type="string", length=255)
     */
    private $headerEn;

    /**
     * @var string
     *
     * @ORM\Column(name="storage_type", type="string", length=255)
     */
    private $storageType;

    /**
     * @var string
     *
     * @ORM\Column(name="form_element_type", type="string", length=255)
     */
    private $formElementType;

//    /**
//     * @ORM\ManyToOne(targetEntity="SubField")
//     * @ORM\JoinColumn(name="id", referencedColumnName="id")
//     */
//    private $entity;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return FieldType
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set storageTypeId
     *
     * @param integer $storageTypeId
     *
     * @return FieldType
     */
    public function setStorageTypeId($storageTypeId)
    {
        $this->storageTypeId = $storageTypeId;

        return $this;
    }

    /**
     * Get storageTypeId
     *
     * @return integer
     */
    public function getStorageTypeId()
    {
        return $this->storageTypeId;
    }

    /**
     * Set storageType
     *
     * @param string $storageType
     *
     * @return FieldType
     */
    public function setStorageType($storageType)
    {
        $this->storageType = $storageType;

        return $this;
    }

    /**
     * Get storageType
     *
     * @return string
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return FieldType
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set formElementType
     *
     * @param string $formElementType
     *
     * @return FieldType
     */
    public function setFormElementType($formElementType)
    {
        $this->formElementType = $formElementType;

        return $this;
    }

    /**
     * Get formElementType
     *
     * @return string
     */
    public function getFormElementType()
    {
        return $this->formElementType;
    }

    /**
     * Set headerEn
     *
     * @param string $headerEn
     *
     * @return FieldType
     */
    public function setHeaderEn($headerEn)
    {
        $this->headerEn = $headerEn;

        return $this;
    }

    /**
     * Get headerEn
     *
     * @return string
     */
    public function getHeaderEn()
    {
        return $this->headerEn;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return FieldType
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
