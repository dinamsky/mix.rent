<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CardFeature
 *
 * @ORM\Table(name="card_feature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardFeatureRepository")
 */
class CardFeature
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
     * @var int
     *
     * @ORM\Column(name="feature_id", type="bigint")
     */
    private $featureId;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Card", inversedBy="cardFeatures")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $card;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feature", inversedBy="cardFeatures")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     */
    private $feature;


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
     * @return CardFeature
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
     * Set featureId
     *
     * @param integer $featureId
     *
     * @return CardFeature
     */
    public function setFeatureId($featureId)
    {
        $this->featureId = $featureId;

        return $this;
    }

    /**
     * Get featureId
     *
     * @return int
     */
    public function getFeatureId()
    {
        return $this->featureId;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return CardFeature
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
     * Set feature
     *
     * @param \AppBundle\Entity\Feature $feature
     *
     * @return CardFeature
     */
    public function setFeature(\AppBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return \AppBundle\Entity\Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }
}
