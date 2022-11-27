<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Feature
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature
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
     * @ORM\Column(name="group_name", type="string", length=255)
     */
    private $groupName;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="bigint", nullable=true)
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="header_en", type="string", length=255)
     */
    private $headerEn;

    /**
     * @var string
     *
     * @ORM\Column(name="gts", type="string", length=255)
     */
    private $gts;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feature", mappedBy="parent")
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feature", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CardFeature", mappedBy="feature", cascade={"remove"}, orphanRemoval=true)
     */
    private $cardFeatures;

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->cardFeatures = new ArrayCollection();
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
     * Set groupName
     *
     * @param string $groupName
     *
     * @return Feature
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Feature
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
     * @return Feature
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
     * @param \AppBundle\Entity\Feature $child
     *
     * @return Feature
     */
    public function addChild(\AppBundle\Entity\Feature $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Feature $child
     */
    public function removeChild(\AppBundle\Entity\Feature $child)
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
     * @param \AppBundle\Entity\Feature $parent
     *
     * @return Feature
     */
    public function setParent(\AppBundle\Entity\Feature $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Feature
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add cardFeature
     *
     * @param \AppBundle\Entity\CardFeature $cardFeature
     *
     * @return Feature
     */
    public function addCardFeature(\AppBundle\Entity\CardFeature $cardFeature)
    {
        $this->cardFeatures[] = $cardFeature;

        return $this;
    }

    /**
     * Remove cardFeature
     *
     * @param \AppBundle\Entity\CardFeature $cardFeature
     */
    public function removeCardFeature(\AppBundle\Entity\CardFeature $cardFeature)
    {
        $this->cardFeatures->removeElement($cardFeature);
    }

    /**
     * Get cardFeatures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardFeatures()
    {
        return $this->cardFeatures;
    }

    /**
     * Set headerEn
     *
     * @param string $headerEn
     *
     * @return Feature
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
     * Set gts
     *
     * @param string $gts
     *
     * @return Feature
     */
    public function setGts($gts)
    {
        $this->gts = $gts;

        return $this;
    }

    /**
     * Get gts
     *
     * @return string
     */
    public function getGts()
    {
        return $this->gts;
    }
}
