<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceRepository")
 */
class Price
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CardPrice", mappedBy="price", cascade={"remove"}, orphanRemoval=true)
     */
    private $cardPrices;

    public function __construct() {
        $this->cardPrices = new ArrayCollection();
    }

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
     * @return Price
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
     * Add cardPrice
     *
     * @param \AppBundle\Entity\CardPrice $cardPrice
     *
     * @return Price
     */
    public function addCardPrice(\AppBundle\Entity\CardPrice $cardPrice)
    {
        $this->cardPrices[] = $cardPrice;

        return $this;
    }

    /**
     * Remove cardPrice
     *
     * @param \AppBundle\Entity\CardPrice $cardPrice
     */
    public function removeCardPrice(\AppBundle\Entity\CardPrice $cardPrice)
    {
        $this->cardPrices->removeElement($cardPrice);
    }

    /**
     * Get cardPrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardPrices()
    {
        return $this->cardPrices;
    }
}
