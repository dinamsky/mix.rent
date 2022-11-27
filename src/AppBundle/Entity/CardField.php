<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CardField
 *
 * @ORM\Table(name="card_field")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardFieldRepository")
 */
class CardField
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="field_id", type="bigint")
     */
    private $fieldId;

    /**
     * @ORM\ManyToOne(targetEntity="FieldType")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $fieldType;

    /**
     * @var int
     *
     * @ORM\Column(name="general_type_id", type="integer")
     */
    private $generalTypeId;

    private $selects;

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
     * Set fieldId
     *
     * @param integer $fieldId
     *
     * @return CardField
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    /**
     * Get fieldId
     *
     * @return int
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Set generalTypeId
     *
     * @param integer $generalTypeId
     *
     * @return CardField
     */
    public function setGeneralTypeId($generalTypeId)
    {
        $this->generalTypeId = $generalTypeId;

        return $this;
    }

    /**
     * Get generalTypeId
     *
     * @return int
     */
    public function getGeneralTypeId()
    {
        return $this->generalTypeId;
    }

    /**
     * Set fieldType
     *
     * @param \AppBundle\Entity\FieldType $fieldType
     *
     * @return CardField
     */
    public function setFieldType(\AppBundle\Entity\FieldType $fieldType = null)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return \AppBundle\Entity\FieldType
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    public function setSelects($selects)
    {
        $this->selects = $selects;

        return $this;
    }

    public function getSelects()
    {
        return $this->selects;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CardField
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
