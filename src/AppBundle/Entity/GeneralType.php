<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GeneralType
 *
 * @ORM\Table(name="general_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GeneralTypeRepository")
 */
class GeneralType
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
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="header_singular", type="string", length=255, nullable=true)
     */
    private $headerSingular;

    /**
     * @var string
     *
     * @ORM\Column(name="singular_en", type="string", length=255)
     */
    private $singularEn;

    /**
     * @var string
     *
     * @ORM\Column(name="car_type_ids", type="string", length=255)
     */
    private $carTypeIds;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="chego_singular", type="string", length=255)
     */
    private $chegoSingular;

    /**
     * @var string
     *
     * @ORM\Column(name="chego_plural", type="string", length=255)
     */
    private $chegoPlural;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="GeneralType", mappedBy="parent")
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="GeneralType", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Card", mappedBy="generalType", cascade={"remove"}, orphanRemoval=true)
     */
    private $cards;

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->cards = new ArrayCollection();
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return GeneralType
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return GeneralType
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
     * Add child
     *
     * @param \AppBundle\Entity\GeneralType $child
     *
     * @return GeneralType
     */
    public function addChild(\AppBundle\Entity\GeneralType $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\GeneralType $child
     */
    public function removeChild(\AppBundle\Entity\GeneralType $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\GeneralType $parent
     *
     * @return GeneralType
     */
    public function setParent(\AppBundle\Entity\GeneralType $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\GeneralType
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return GeneralType
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return GeneralType
     */
    public function addCard(\AppBundle\Entity\Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \AppBundle\Entity\Card $card
     */
    public function removeCard(\AppBundle\Entity\Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Set carTypeIds
     *
     * @param string $carTypeIds
     *
     * @return GeneralType
     */
    public function setCarTypeIds($carTypeIds)
    {
        $this->carTypeIds = $carTypeIds;

        return $this;
    }

    /**
     * Get carTypeIds
     *
     * @return string
     */
    public function getCarTypeIds()
    {
        return $this->carTypeIds;
    }

    /**
     * Set chegoSingular
     *
     * @param string $chegoSingular
     *
     * @return GeneralType
     */
    public function setChegoSingular($chegoSingular)
    {
        $this->chegoSingular = $chegoSingular;

        return $this;
    }

    /**
     * Get chegoSingular
     *
     * @return string
     */
    public function getChegoSingular()
    {
        return $this->chegoSingular;
    }

    /**
     * Set chegoPlural
     *
     * @param string $chegoPlural
     *
     * @return GeneralType
     */
    public function setChegoPlural($chegoPlural)
    {
        $this->chegoPlural = $chegoPlural;

        return $this;
    }

    /**
     * Get chegoPlural
     *
     * @return string
     */
    public function getChegoPlural()
    {
        return $this->chegoPlural;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return GeneralType
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return GeneralType
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set headerSingular
     *
     * @param string $headerSingular
     *
     * @return GeneralType
     */
    public function setHeaderSingular($headerSingular)
    {
        $this->headerSingular = $headerSingular;

        return $this;
    }

    /**
     * Get headerSingular
     *
     * @return string
     */
    public function getHeaderSingular()
    {
        return $this->headerSingular;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return GeneralType
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set singularEn
     *
     * @param string $singularEn
     *
     * @return GeneralType
     */
    public function setSingularEn($singularEn)
    {
        $this->singularEn = $singularEn;

        return $this;
    }

    /**
     * Get singularEn
     *
     * @return string
     */
    public function getSingularEn()
    {
        return $this->singularEn;
    }
}
