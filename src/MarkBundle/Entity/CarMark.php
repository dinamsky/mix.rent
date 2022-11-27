<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarMark
 *
 * @ORM\Table(name="car_mark")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarMarkRepository")
 */
class CarMark
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
     * @ORM\OneToMany(targetEntity="MarkBundle\Entity\CarModel", mappedBy="mark", fetch="EXTRA_LAZY", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"header" = "ASC"})
     */
    private $models;

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
     * Set carTypeId
     *
     * @param integer $carTypeId
     *
     * @return CarMark
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
     * @return CarMark
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
     * Constructor
     */
    public function __construct()
    {
        $this->models = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add model
     *
     * @param \MarkBundle\Entity\CarModel $model
     *
     * @return CarMark
     */
    public function addModel(\MarkBundle\Entity\CarModel $model)
    {
        $this->models[] = $model;

        return $this;
    }

    /**
     * Remove model
     *
     * @param \MarkBundle\Entity\CarModel $model
     */
    public function removeModel(\MarkBundle\Entity\CarModel $model)
    {
        $this->models->removeElement($model);
    }

    /**
     * Get models
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModels()
    {
        return $this->models;
    }
}
