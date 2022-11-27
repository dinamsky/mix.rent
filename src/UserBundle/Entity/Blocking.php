<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blocking
 *
 * @ORM\Table(name="blocking")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\BlockingRepository")
 */
class Blocking
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
     * @ORM\Column(name="user_id", type="bigint")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="visitor_id", type="bigint")
     */
    private $visitorId;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Blocking
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
     * Set visitorId
     *
     * @param integer $visitorId
     *
     * @return Blocking
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
}

