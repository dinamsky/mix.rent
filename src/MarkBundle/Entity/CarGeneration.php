<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarGeneration
 *
 * @ORM\Table(name="car_generation")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarGenerationRepository")
 */
class CarGeneration
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
     * @ORM\Column(name="car_type_id", type="integer")
     */
    private $carTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="year_begin", type="string", length=255, nullable=true)
     */
    private $yearBegin;

    /**
     * @var string
     *
     * @ORM\Column(name="year_end", type="string", length=255, nullable=true)
     */
    private $yearEnd;

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
     * @return CarGeneration
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
     * @return CarGeneration
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
     * Set yearBegin
     *
     * @param string $yearBegin
     *
     * @return CarGeneration
     */
    public function setYearBegin($yearBegin)
    {
        $this->yearBegin = $yearBegin;

        return $this;
    }

    /**
     * Get yearBegin
     *
     * @return string
     */
    public function getYearBegin()
    {
        return $this->yearBegin;
    }

    /**
     * Set yearEnd
     *
     * @param string $yearEnd
     *
     * @return CarGeneration
     */
    public function setYearEnd($yearEnd)
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    /**
     * Get yearEnd
     *
     * @return string
     */
    public function getYearEnd()
    {
        return $this->yearEnd;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return CarGeneration
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

