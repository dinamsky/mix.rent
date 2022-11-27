<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldInteger
 *
 * @ORM\Table(name="field_integer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FieldIntegerRepository")
 */
class FieldInteger
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
     * @var int
     *
     * @ORM\Column(name="card_id", type="bigint")
     */
    private $cardId;

    /**
     * @ORM\ManyToOne(targetEntity="Card", inversedBy="fieldIntegers", cascade={"remove"})
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $card;

    /**
     * @var int
     *
     * @ORM\Column(name="card_field_id", type="integer")
     */
    private $cardFieldId;




    /**
     * @var int
     *
     * @ORM\Column(name="value", type="bigint")
     */
    private $value;


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
     * Set cardId
     *
     * @param integer $cardId
     *
     * @return FieldInteger
     */
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;

        return $this;
    }

    /**
     * Get cardId
     *
     * @return int
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * Set cardFieldId
     *
     * @param integer $cardFieldId
     *
     * @return FieldInteger
     */
    public function setCardFieldId($cardFieldId)
    {
        $this->cardFieldId = $cardFieldId;

        return $this;
    }

    /**
     * Get cardFieldId
     *
     * @return int
     */
    public function getCardFieldId()
    {
        return $this->cardFieldId;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return FieldInteger
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set subField
     *
     * @param \AppBundle\Entity\SubField $subField
     *
     * @return FieldInteger
     */
    public function setSubField(\AppBundle\Entity\SubField $subField = null)
    {
        $this->subField = $subField;

        return $this;
    }

    /**
     * Get subField
     *
     * @return \AppBundle\Entity\SubField
     */
    public function getSubField()
    {
        return $this->subField;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return FieldInteger
     */
    public function setCard(\AppBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \AppBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
