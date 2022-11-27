<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Deleted
 *
 * @ORM\Table(name="deleted")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeletedRepository")
 */
class Deleted
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
     * @ORM\Column(name="user_id", type="bigint")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="city_id", type="bigint")
     */
    private $cityId;

    /**
     * @var int
     *
     * @ORM\Column(name="model_id", type="integer")
     */
    private $modelId;

    /**
     * @var int
     *
     * @ORM\Column(name="general_type_id", type="integer")
     */
    private $generalTypeId;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_delete", type="datetime", nullable=true)
     */
    private $dateDelete;


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
     * @return Deleted
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Deleted
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Deleted
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return int
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set modelId
     *
     * @param integer $modelId
     *
     * @return Deleted
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Get modelId
     *
     * @return int
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set generalTypeId
     *
     * @param integer $generalTypeId
     *
     * @return Deleted
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
     * Set dateDelete
     *
     * @param \DateTime $dateDelete
     *
     * @return Deleted
     */
    public function setDateDelete($dateDelete)
    {
        $this->dateDelete = $dateDelete;

        return $this;
    }

    /**
     * Get dateDelete
     *
     * @return \DateTime
     */
    public function getDateDelete()
    {
        return $this->dateDelete;
    }
}

