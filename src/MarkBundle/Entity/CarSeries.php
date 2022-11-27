<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarSeries
 *
 * @ORM\Table(name="car_series")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarSeriesRepository")
 */
class CarSeries
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
     * @ORM\Column(name="car_model_id", type="integer")
     */
    private $carModelId;

    /**
     * @var int
     *
     * @ORM\Column(name="car_generation_id", type="integer", nullable=true)
     */
    private $carGenerationId;

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
     * Set carModelId
     *
     * @param integer $carModelId
     *
     * @return CarSeries
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
     * Set carGenerationId
     *
     * @param integer $carGenerationId
     *
     * @return CarSeries
     */
    public function setCarGenerationId($carGenerationId)
    {
        $this->carGenerationId = $carGenerationId;

        return $this;
    }

    /**
     * Get carGenerationId
     *
     * @return int
     */
    public function getCarGenerationId()
    {
        return $this->carGenerationId;
    }

    /**
     * Set carTypeId
     *
     * @param integer $carTypeId
     *
     * @return CarSeries
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
     * @return CarSeries
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

