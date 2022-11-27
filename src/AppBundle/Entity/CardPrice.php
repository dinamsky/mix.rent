<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CardPrice
 *
 * @ORM\Table(name="card_price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardPriceRepository")
 */
class CardPrice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
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
     * @var int
     *
     * @ORM\Column(name="price_id", type="bigint")
     */
    private $priceId;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Card", inversedBy="cardPrices")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $card;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Price", inversedBy="cardPrices")
     * @ORM\JoinColumn(name="price_id", referencedColumnName="id")
     */
    private $price;

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
     * @return CardPrice
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
     * Set priceId
     *
     * @param integer $priceId
     *
     * @return CardPrice
     */
    public function setPriceId($priceId)
    {
        $this->priceId = $priceId;

        return $this;
    }

    /**
     * Get priceId
     *
     * @return int
     */
    public function getPriceId()
    {
        return $this->priceId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return CardPrice
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return CardPrice
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

    /**
     * Set price
     *
     * @param \AppBundle\Entity\Price $price
     *
     * @return CardPrice
     */
    public function setPrice(\AppBundle\Entity\Price $price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \AppBundle\Entity\Price
     */
    public function getPrice()
    {
        return $this->price;
    }
}
