<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarModification
 *
 * @ORM\Table(name="car_modification")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarModificationRepository")
 */
class CarModification
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
     * @ORM\Column(name="car_series_id", type="integer")
     */
    private $carSeriesId;

    /**
     * @var int
     *
     * @ORM\Column(name="car_model_id", type="integer")
     */
    private $carModelId;

    /**
     * @var int
     *
     * @ORM\Column(name="car_type_id", type="integer")
     */
    private $carTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;


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
     * Set carSeriesId
     *
     * @param integer $carSeriesId
     *
     * @return CarModification
     */
    public function setCarSeriesId($carSeriesId)
    {
        $this->carSeriesId = $carSeriesId;

        return $this;
    }

    /**
     * Get carSeriesId
     *
     * @return int
     */
    public function getCarSeriesId()
    {
        return $this->carSeriesId;
    }

    /**
     * Set carModelId
     *
     * @param integer $carModelId
     *
     * @return CarModification
     */
    public function setCarModelId($carModelId)
    {
        $this->carModelId = $carModelId;

        return $this;
    }

    /**
     * Get carModelId
     *
     * @return int
     */
    public function getCarModelId()
    {
        return $this->carModelId;
    }

    /**
     * Set carTypeId
     *
     * @param integer $carTypeId
     *
     * @return CarModification
     */
    public function setCarTypeId($carTypeId)
    {
        $this->carTypeId = $carTypeId;

        return $this;
    }

    /**
     * Get carTypeId
     *
     * @return int
     */
    public function getCarTypeId()
    {
        return $this->carTypeId;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return CarModification
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
}

