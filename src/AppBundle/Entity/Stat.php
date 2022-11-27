<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Stat
 *
 * @ORM\Table(name="stat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatRepository")
 */
class Stat
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="page_type", type="string", length=255, nullable=true)
     */
    private $pageType;

    /**
     * @var string
     *
     * @ORM\Column(name="event_type", type="string", length=255, nullable=true)
     */
    private $eventType;

    /**
     * @var int
     *
     * @ORM\Column(name="visitor_id", type="bigint", nullable=true)
     */
    private $visitorId;

    /**
     * @var string
     *
     * @ORM\Column(name="visitor_type", type="string", length=255, nullable=true)
     */
    private $visitorType;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="bigint", nullable=true)
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="bigint", nullable=true)
     */
    private $qty;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @var int
     *
     * @ORM\Column(name="card_id", type="bigint", nullable=true)
     */
    private $cardId;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_empty", type="boolean", nullable=true)
     */
    private $isEmpty;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_mixrent", type="boolean", options={"default" : 0})
     */

    private $isMixrent = 0;


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
     * Set url
     *
     * @param string $url
     *
     * @return Stat
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
     * Set pageType
     *
     * @param string $pageType
     *
     * @return Stat
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }

    /**
     * Get pageType
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * Set eventType
     *
     * @param string $eventType
     *
     * @return Stat
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set visitorId
     *
     * @param integer $visitorId
     *
     * @return Stat
     */
    public function setVisitorId($visitorId)
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    /**
     * Get visitorId
     *
     * @return int
     */
    public function getVisitorId()
    {
        return $this->visitorId;
    }

    /**
     * Set visitorType
     *
     * @param string $visitorType
     *
     * @return Stat
     */
    public function setVisitorType($visitorType)
    {
        $this->visitorType = $visitorType;

        return $this;
    }

    /**
     * Get visitorType
     *
     * @return string
     */
    public function getVisitorType()
    {
        return $this->visitorType;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Stat
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return Stat
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Stat
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set cardId
     *
     * @param integer $cardId
     *
     * @return Stat
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
     * Set isEmpty
     *
     * @param boolean $isEmpty
     *
     * @return Stat
     */
    public function setIsEmpty($isEmpty)
    {
        $this->isEmpty = $isEmpty;

        return $this;
    }

    /**
     * Get isEmpty
     *
     * @return bool
     */
    public function getIsEmpty()
    {
        return $this->isEmpty;
    }

    /**
     * Set isMixrent
     *
     * @param boolean $isMixrent
     *
     * @return Stat
     */
    public function setIsMixrent($isMixrent)
    {
        $this->isMixrent = $isMixrent;

        return $this;
    }

    /**
     * Get isMixrent
     *
     * @return boolean
     */
    public function getIsMixrent()
    {
        return $this->isMixrent;
    }
}
