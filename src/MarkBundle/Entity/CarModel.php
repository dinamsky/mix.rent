<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarModel
 *
 * @ORM\Table(name="car_model")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarModelRepository")
 */
class CarModel
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
     * @ORM\Column(name="car_mark_id", type="integer")
     */
    private $carMarkId;

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
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="MarkBundle\Entity\CarMark", inversedBy="models", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="car_mark_id", referencedColumnName="id")
     */
    private $mark;

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
     * Set carMarkId
     *
     * @param integer $carMarkId
     *
     * @return CarModel
     */
    public function setCarMarkId($carMarkId)
    {
        $this->carMarkId = $carMarkId;

        return $this;
    }

    /**
     * Get carMarkId
     *
     * @return int
     */
    public function getCarMarkId()
    {
        return $this->carMarkId;
    }

    /**
     * Set carTypeId
     *
     * @param integer $carTypeId
     *
     * @return CarModel
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
     * @return CarModel
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
     * Set mark
     *
     * @param \MarkBundle\Entity\CarMark $mark
     *
     * @return CarModel
     */
    public function setMark(\MarkBundle\Entity\CarMark $mark = null)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return \MarkBundle\Entity\CarMark
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return CarModel
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
}
