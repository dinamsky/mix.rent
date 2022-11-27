<?php

namespace MarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarCharacteristicValue
 *
 * @ORM\Table(name="car_characteristic_value")
 * @ORM\Entity(repositoryClass="MarkBundle\Repository\CarCharacteristicValueRepository")
 */
class CarCharacteristicValue
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
     * @ORM\Column(name="car_characteristic_id", type="integer")
     */
    private $carCharacteristicId;

    /**
     * @var int
     *
     * @ORM\Column(name="car_modification_id", type="integer")
     */
    private $carModificationId;

    /**
     * @var int
     *
     * @ORM\Column(name="car_type_id", type="integer")
     */
    private $carTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;


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
     * Set carCharacteristicId
     *
     * @param integer $carCharacteristicId
     *
     * @return CarCharacteristicValue
     */
    public function setCarCharacteristicId($carCharacteristicId)
    {
        $this->carCharacteristicId = $carCharacteristicId;

        return $this;
    }

    /**
     * Get carCharacteristicId
     *
     * @return int
     */
    public function getCarCharacteristicId()
    {
        return $this->carCharacteristicId;
    }

    /**
     * Set carModificationId
     *
     * @param integer $carModificationId
     *
     * @return CarCharacteristicValue
     */
    public function setCarModificationId($carModificationId)
    {
        $this->carModificationId = $carModificationId;

        return $this;
    }

    /**
     * Get carModificationId
     *
     * @return int
     */
    public function getCarModificationId()
    {
        return $this->carModificationId;
    }

    /**
     * Set carTypeId
     *
     * @param integer $carTypeId
     *
     * @return CarCharacteristicValue
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
     * Set unit
     *
     * @param string $unit
     *
     * @return CarCharacteristicValue
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return CarCharacteristicValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

